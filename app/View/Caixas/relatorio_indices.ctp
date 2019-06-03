<?php $this->layout = 'naoLogado'; ?>

<?php $column_chart->div('chart_div'); ?>
<?php $column_chart_linha->div('chart_div_linha'); ?>
<?php $piechart->div('pie_chart_div'); ?>

<div id="chart_div">
    <?php $this->GoogleCharts->createJsChart($column_chart); ?>
</div>
<div id="chart_div_linha">
    <?php $this->GoogleCharts->createJsChart($column_chart_linha); ?>
</div>
<div id="pie_chart_div">
    <?php $this->GoogleCharts->createJsChart($piechart); ?>
</div>
