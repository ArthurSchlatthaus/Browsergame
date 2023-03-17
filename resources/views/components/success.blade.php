<div id="successAlert" class="alert alert-success m-4" role="alert" style="display: none; position:absolute;left: 80%;transform: translateX(-80%); top: 20px">
</div>
<script>
    $("body").click(function () {
        if (document.getElementById("successAlert") != null && document.getElementById("successAlert").style.display === 'block') {
            document.getElementById("successAlert").style.display = "none";
        }
    });
</script>
<style>
    .alert {
        font-size: larger;
    }
</style>