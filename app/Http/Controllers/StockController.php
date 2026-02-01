<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;  
use File;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
    use Illuminate\Support\Facades\Auth;
class StockController extends Controller implements HasMiddleware
{

public static function middleware(): array
    {
        return static::middlewares();
    }public static function middlewares(): array
{
    return [
        new Middleware(middleware: 'auth'),

        // 'stocks' ki jagah 'permission' likhein kyunki app.php mein wahi alias hai
        new Middleware(middleware: 'permission:view stocks', only: ['index']),

        new Middleware(middleware: 'permission:create stocks', only: ['create', 'store']),

        new Middleware(middleware: 'permission:edit stocks', only: ['edit', 'update']),

        new Middleware(middleware: 'permission:delete stocks', only: ['delete']),
    ];    
}


/**
 * Category ID ke basis par Items fetch karne ke liye (AJAX)
 */
public function getItemsByCategory($catId)
{
    try {
        // Raw DB query use kar rahe hain speed ke liye
        $items = DB::table('product_item_master')
            ->where('category_id', $catId)
            ->select('id', 'item_name', 'item_code')
            ->orderBy('item_name', 'asc')
            ->get();

        // Agar items milte hain toh JSON return karein
        return response()->json([
            'success' => true,
            'data'    => $items
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false, 
            'message' => 'Bhai, items fetch karne mein error aa gaya: ' . $e->getMessage()
        ], 500);
    }
}


public function printInvoice($id) {
    $stock = DB::table('stocks') // apni table ka naam check kar lein
        ->leftJoin('product_item_master', 'stocks.item_id', '=', 'product_item_master.id')
        ->select('stocks.*', 'product_item_master.item_name')
        ->where('stocks.id', $id)
        ->first();

    if (!$stock) { return redirect()->back()->with('error', 'Record not found'); }

    return view('backend.pages.stock.invoice', compact('stock'));
}
 public function index()
{
    // Joins use karne se page load speed 10x fast ho jayegi
    $stocks = DB::table('stocks')
        ->leftJoin('company_list', 'stocks.company', '=', 'company_list.id')
        ->leftJoin('product_category_master', 'stocks.product_category', '=', 'product_category_master.id')
        ->select('stocks.*', 'company_list.company_name', 'product_category_master.name as category_name')
        ->orderBy('stocks.id', 'desc')
        ->get();

    $company_list = DB::table('company_list')->get();
    $category_master = DB::table('product_category_master')->get();
     $seller_masters = DB::table('seller_masters')->get();

    // dd($stocks);

    return view('backend.pages.stock.index', compact('stocks', 'company_list', 'category_master','seller_masters'));
}
    public function store(Request $request)
    {
        return $this->saveStock($request);
    }


 public function saveStock(Request $request, $id = null)
{
    // 1. Validation
    $rules = [
        'product_name' => 'required|string|max:255',
        'company_id' => 'required',
        'category_id' => 'required',
        'item_id' => 'required',
        'receipt_no' => 'required',
        'seller_id' => 'required',
        // 'seller_name' => 'required',
        'seller_contact' => 'required',
        'payer_name' => 'required',
        'payer_contact' => 'required',
        'receiver_name' => 'required',
        'receiver_contact' => 'required',
        'mrp' => 'required|numeric',
        'purchase_price' => 'required|numeric',
        'product_img.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        'product_video' => 'nullable|mimes:mp4,mov,ogg,qt|max:20480',
        'warranty_card' => 'nullable|mimes:jpeg,png,pdf|max:2048',
        'invoice_file.*' => 'nullable|mimes:pdf,jpeg,png,jpg|max:2048',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors(),
            'message' => 'Please fix the errors below.'
        ], 422);
    }


 $cat = DB::table('product_category_master')
        ->where('id', $request->category_id)
        ->first();
$catCode = $cat ? strtoupper($cat->code) : 'XX';

$item = DB::table('product_item_master')
        ->where('id', $request->item_id)
        ->first();
$itemCode = $item ? strtoupper($item->item_code) : 'YY';

/*
|--------------------------------------------------------------------------
| Count existing records for this Cat + Item
|--------------------------------------------------------------------------
*/
$totalCount = DB::table('stocks')
    ->where('product_category', $request->category_id)
    ->where('item_id', $request->item_id)
    ->count();

/*
|--------------------------------------------------------------------------
| Determine Alphabet Prefix (A, B, C...)
|--------------------------------------------------------------------------
*/
$seriesIndex = floor($totalCount / 99999); // 0 = A, 1 = B, 2 = C
$prefixLetter = chr(65 + $seriesIndex);   // 65 = ASCII A

/*
|--------------------------------------------------------------------------
| Running number inside that alphabet block
|--------------------------------------------------------------------------
*/
$runningNumber = ($totalCount % 99999) + 1;
$formattedNum = str_pad($runningNumber, 5, '0', STR_PAD_LEFT);

/*
|--------------------------------------------------------------------------
| Final Unique ID
|--------------------------------------------------------------------------
*/
$unique_id = $prefixLetter . '-' . $catCode . '-' . $itemCode . '-' . $formattedNum;


       //dd($unique_id);
    // 2. Prepare Data (Database columns ke mutabik)
    $insertData = [
        'receipt_no'          => $request->receipt_no,
        'product_name'        => $request->product_name,
        'product_category'    => $request->category_id, // Table column: product_category
        'item_id'             => $request->item_id,
        'company'             => $request->company_id,  // Table column: company
        'condition_type'      => $request->condition_type,
        'warranty_start_date' => $request->warranty_start_date,
        'warranty_years'      => $request->warranty_years ?? 0,
        'warranty_end_date'   => $request->warranty_end_date,
        'mrp'                 => $request->mrp,
        'purchase_price'      => $request->purchase_price,
        'seller_id'          => $request->seller_id,
        'seller_name'         => $request->seller_name,
        'seller_contact'      => $request->seller_contact,
        'payer_name'          => $request->payer_name,
        'payer_contact'       => $request->payer_contact,
        'receiver_name'       => $request->receiver_name,
        'receiver_contact'    => $request->receiver_contact,
        'unique_id'          => $unique_id,
        'updated_at'          => now()
    ];

    // 3. File Upload Logic
    $fileFields = ['product_img', 'product_video', 'warranty_card', 'invoice_file'];
    foreach ($fileFields as $field) {
        if ($request->hasFile($field)) {
            $fileInput = $request->file($field);
            if (is_array($fileInput)) {
                $paths = [];
                foreach ($fileInput as $file) {
                    $name = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads/stock'), $name);
                    $paths[] = $name;
                }
                $insertData[$field] = implode(',', $paths);
            } else {
                $name = time() . '_' . uniqid() . '.' . $fileInput->getClientOriginalExtension();
                $fileInput->move(public_path('uploads/stock'), $name);
                $insertData[$field] = $name;
            }
        }
    }

    // 4. Finalize
    try {
        if ($id) {
            DB::table('stocks')->where('id', $id)->update($insertData);
            return response()->json(['success' => true, 'message' => 'Stock Updated Successfully!']);
        } else {
          
            $insertData['created_at'] = now();
            DB::table('stocks')->insert($insertData);
            return response()->json(['success' => true, 'message' => 'Stock Added Successfully!']);
        }
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Database Error: ' . $e->getMessage()], 500);
    }
}

    // Update Stock Method
// Update Route logic
    public function updateStock(Request $request)
    {
        return $this->saveStock($request, $request->id);
    }

    // Delete Route logic
    public function deleteStock(Request $request)
    {
        $id = $request->id;
        $stock = DB::table('stocks')->where('id', $id)->first();

        if ($stock) {
            // Clear files from folder
            $filesCols = ['product_img', 'product_video', 'warranty_card', 'invoice_file'];
            foreach ($filesCols as $col) {
                if ($stock->$col && $stock->$col != 'NA') {
                    $files = explode(',', $stock->$col);
                    foreach ($files as $file) {
                        if (File::exists(public_path('uploads/stock/' . $file))) {
                            File::delete(public_path('uploads/stock/' . $file));
                        }
                    }
                }
            }
            DB::table('stocks')->where('id', $id)->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }



















}