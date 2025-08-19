<div class="card card-body">
    <h4 class="card-title">Filter</h4>
    <form action="{{ route('admin.admins.index') }}" method="GET" id="filterForm">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="name">Keyword</label>
                    <input type="text" name="keyword" id="keyword" class="form-control"
                        value="{{ app('request')->query('keyword') }}" placeholder="Enter keyword">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control select2">
                        <option value="">All</option>
                        <option value="0" {{ app('request')->query('status') == '0' ? 'selected' : '' }}>Inactive</option>
                        <option value="1" {{ app('request')->query('status') == '1' ? 'selected' : '' }}>Active</option>
                    </select>
                </div>
            </div>
            <div class="col-auto mt-1 text-right">
                <div class="form-group">
                    <label for="created_at">&nbsp;</label>
                    <button type="submit" form="filterForm" class="btn btn-primary mt-4">Filter</button>
                    <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary mt-4">Reset</a>
                </div>
            </div>
        </div>
    </form>
</div>