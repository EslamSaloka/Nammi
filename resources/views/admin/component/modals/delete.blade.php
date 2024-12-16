<div class="modal" id="deleteModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">@lang('Delete Data')</h6>
                <button aria-label="Close" class="btn btn-sm" data-bs-dismiss="modal" type="button">
                    <i class="fa fa-close"></i>
                </button>
            </div>
            <form id="deleteForm" action="" method="POST" enctype="multipart/form-data">
                @method('DELETE')
                @csrf
                <div class="modal-body">
                    @lang('Are you sure to delete data')
                </div>
                <div class="modal-footer">
                    <button class="btn ripple btn-primary" type="submit">@lang('Yes')</button>
                    <button type="button" class="btn ripple btn-secondary" data-bs-dismiss="modal">@lang('No')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
<script>
    $(".delete_row").click(function() {
        $("#deleteModal").modal('show');
    });
    function deleteRow(route)
    {
        $('#deleteForm').attr('action', route)
    }
</script>
@endpush
