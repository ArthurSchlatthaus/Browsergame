<div class="toast-container top-0 end-0 position-absolute p-1">
    <div id="notifyToast" class="notifyToast toast" role="status" aria-live="polite" aria-atomic="true">
        <div class="toast-header align-items-center justify-content-center" id="notifyText">
            {!!Session::get('notify')!!}
        </div>
    </div>
</div>
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function () {
        let toast = $('#notifyToast').toast()
        toast.hide()
    });
    $("body").click(function () {
        let toast = $('#notifyToast').toast()
        toast.hide()
    });
</script>
