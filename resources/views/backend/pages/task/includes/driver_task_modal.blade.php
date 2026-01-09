<div class="modal fade" id="taskModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-white border border-warning">

            <div class="modal-header border-warning">
                <h5 class="modal-title text-warning" id="taskTitle">Add Task</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form id="taskForm" method="post">
                @csrf
                <input type="hidden" name="id" id="id">

                <div class="modal-body">

                    <div class="mb-2">
                        <label class="text-warning">Client Name</label>
                        <input class="form-control bg-secondary text-white" name="client_name" id="client_name" required>
                    </div>

                    <div class="mb-2">
                        <label class="text-warning">Client Mobile</label>
                        <input class="form-control bg-secondary text-white" name="client_mobile" id="client_mobile">
                    </div>

                    <div class="mb-2">
                        <label class="text-warning">Pickup Location</label>
                        <input class="form-control bg-secondary text-white" name="pickup_location" id="pickup_location" required>
                    </div>

                    <div class="mb-2">
                        <label class="text-warning">Drop Location</label>
                        <input class="form-control bg-secondary text-white" name="drop_location" id="drop_location">
                    </div>

                    <div class="mb-2">
                        <label class="text-warning">Car Number</label>
                        <input class="form-control bg-secondary text-white" name="car_number" id="car_number" required>
                    </div>

                    <div class="mb-2">
                        <label class="text-warning">Task Time</label>
                        <input type="datetime-local" class="form-control bg-secondary text-white" name="task_time" id="task_time" required>
                    </div>

                    <div class="mb-2">
                        <label class="text-warning">Status</label>
                        <select class="form-select bg-secondary text-white" name="status" id="status">
                            <option value="pending">Pending</option>
                            <option value="ongoing">Ongoing</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="text-warning">Remarks</label>
                        <textarea class="form-control bg-secondary text-white" name="remarks" id="remarks"></textarea>
                    </div>

                </div>

                <div class="modal-footer border-warning">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning text-dark fw-semibold" onclick="saveTask()">Save</button>
                </div>

            </form>

        </div>
    </div>
</div>
