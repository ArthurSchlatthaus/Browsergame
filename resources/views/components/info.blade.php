<div id="infoAlert" class="alert alert-info m-4" role="alert" style="z-index: 9999;display: none; position:absolute;left: 60%;transform: translateX(-60%); top: 20px">
</div>
<script>
    $("body").click(function () {
        if (document.getElementById("infoAlert") != null && document.getElementById("infoAlert").style.display === 'block') {
            document.getElementById("infoAlert").style.display = "none";
        }
    });
</script>
<style>
    .alert {
        font-size: larger;
    }
</style>
