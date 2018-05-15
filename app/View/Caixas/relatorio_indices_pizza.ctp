<?php $this->layout = 'naoLogado'; ?>

<?php $pie_chart_qtd->div('chart_div'); ?>
<?php $pie_chart_valor->div('valor'); ?>

<div id="chart_div">
    <?php echo $this->GoogleCharts->createJsChart($pie_chart_qtd); ?>
</div>
<br>
<div id="valor">
    <?php echo $this->GoogleCharts->createJsChart($pie_chart_valor); ?>
</div>
