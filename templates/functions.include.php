<?php
function pieChart($id)
{
    ?>
    <div class="pie-chart-container row">
        <div class="canvas-container col-md-7">
            <canvas id="<?php echo $id;?>" class="pieChart chart-<?php echo $id;?>"></canvas>
        </div>
        <div class="legend-container col-md-5"></div>
    </div>
    <?php
}
