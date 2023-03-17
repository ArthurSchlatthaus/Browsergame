<div style="position: absolute; bottom: 60px;right: 10px;">
    <x-equipment></x-equipment>
    <button style="border: none;background-color: transparent;" onclick="window.loadElement('inventoryContainer')">
        <img src="/images/close.png" style="position: absolute;right: 2px;top: 1px; width: 25px;">
    </button>
    <x-inventoryitems></x-inventoryitems>
    <h5 id="playerGold"></h5>
    <script>
        if (window.getGlobalPlayer() != null) {
            document.getElementById("playerGold").innerText = 'Yang: ' + Math.round(window.getGlobalPlayer().gold)
        }
    </script>
</div>


