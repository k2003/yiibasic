<?php
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use dosamigos\datepicker\DateRangePicker;
use dosamigos\highcharts\HighCharts;

$this->title = 'รายได้แยกตามหมวดการรักษา';

$gridColumns = [
    ['class' => 'kartik\grid\SerialColumn'],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],
        'options' => ['style' => 'width:90px;'],
        'attribute' => 'income',
        'header' => 'รหัส'
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-left'],
        'options' => ['style' => 'width:50px;'],
        'attribute' => 'name',
        'header' => 'หมวดค่ารักษา',
        'pageSummary' => 'รวม',
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],
        'options' => ['style' => 'width:50px;'],
        'attribute' => 'ipd',
        'header' => 'ผู้ป่วยใน(คน)',
        'pageSummary' => true,
        'pageSummaryFunc' => GridView::F_SUM,
        'pageSummaryOptions'=>['class'=>'text-center text-warning'],
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],
        'options' => ['style' => 'width:50px;'],
        'attribute' => 'opd',
        'header' => 'ผู้ป่วยนอก(คน)',
        'pageSummary' => true,
        'pageSummaryFunc' => GridView::F_SUM,
        'pageSummaryOptions'=>['class'=>'text-center text-warning'],
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-right'],
        'options' => ['style' => 'width:50px;'],
        'attribute' => 'price',
        'header' => 'ค่ารักษา',
        'format'=>['decimal', 2],
        'pageSummary' => true,
        'pageSummaryFunc' => GridView::F_SUM,
        'pageSummaryOptions'=>['class'=>'text-right text-success'],
    ],
];
?>
<div class="site-index">
    <div class="body-content">
        <div class='well'>
            <div class="row">
                <?php $form = ActiveForm::begin(['id' => 'rpt-form', 'enableClientValidation' => false]); ?>
                <div class="col-lg-2">
                    <strong>วันที่บริการ</strong>
                </div>
                <div class="col-lg-6">            
                <?= DateRangePicker::widget([
                    'language' => 'th',
                    'name' => 'dt1',
                    'value' => date('d-m-Y', strtotime($dt1)),
                    'nameTo' => 'dt2',
                    'valueTo' => date('d-m-Y', strtotime($dt2)),                                        
                    'clientOptions' => [
                        'autoclose' => true,
                        'format' => 'dd-mm-yyyy'
                    ],
                    'size' => 'lg',                    
                ]);?>
                </div>
                <div class="col-lg-2">                    
                    <button class='btn btn-danger'>ประมวลผล</button>            
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <?php yii\widgets\Pjax::begin(); ?>        
        <?php
        echo GridView::widget([
            'dataProvider' => $data,
            'responsive' => true,
            'hover' => true,
            'floatHeader' => true,
            'toolbar'=> [
                ['content'=>                    
                    ExportMenu::widget([
                        'dataProvider' => $data,    
                        'fontAwesome' => true,
                        'showConfirmAlert' => false,
                        'dropdownOptions' => [                            
                            'class' => 'btn btn-default'
                        ]
                    ])
                ],                
                '{toggleData}',
            ],
            'panel' => [
                'before' => 'ประมวลผลล่าสุด '.date('d/m/').(date('Y')+543),
                'type' => 'primary', 'heading' => $this->title
            ],
            'columns' => $gridColumns,
            'showPageSummary' => true,
        ]);
        ?>        
        <?php yii\widgets\Pjax::end(); ?>
    </div>
</div>
<?php
$script = <<< JS
$(function(){
    $('.kv-export-full-form').append('<input type="hidden" name="dt1" value="{$dt1}" />');
    $('.kv-export-full-form').append('<input type="hidden" name="dt2" value="{$dt2}" />');
});
JS;
$this->registerJs($script);
?>