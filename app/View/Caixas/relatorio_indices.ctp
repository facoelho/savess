<?php $this->layout = 'naoLogado'; ?>

<?php $column_chart->div('chart_div'); ?>
<?php $column_chart_linha->div('chart_div_linha'); ?>
<?php $piechart->div('pie_chart_div'); ?>
<?php //$column_chart_qtd->div('chart_div_quantidade'); ?>
<?php //$column_chart_media_peso->div('chart_div_media_peso'); ?>
<?php //$column_chart_media_kg->div('chart_div_media_kg'); ?>

<div id="chart_div">
    <?php $this->GoogleCharts->createJsChart($column_chart); ?>
</div>
<div id="chart_div_linha">
    <?php $this->GoogleCharts->createJsChart($column_chart_linha); ?>
</div>
<div id="pie_chart_div">
    <?php $this->GoogleCharts->createJsChart($piechart); ?>
</div>
<!--<div id="chart_div_quantidade">
<?php //$this->GoogleCharts->createJsChart($column_chart_qtd); ?>
</div>
<div id="chart_div_media_peso">
<?php //$this->GoogleCharts->createJsChart($column_chart_media_peso); ?>
</div>
<div id="chart_div_media_kg">
<?php //$this->GoogleCharts->createJsChart($column_chart_media_kg); ?>
</div>-->