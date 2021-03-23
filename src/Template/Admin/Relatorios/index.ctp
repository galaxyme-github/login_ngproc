<?php $this->assign('title', 'Relatórios');

/*
Menu
 */
?>
<style>
    .box-cashes{
        margin-left:0px;
        max-width:950px;
    }
    .box-cash{
        float:left;
        width: 30%;
        height: 130px;
        max-width: 326px;
        max-height: 326px;
        margin: 0 0 30px 0;
        padding: 15px 50px;
        border: 1px solid rgba(0, 66, 104, .5);
        background-color: #fff;
        border-radius: 5px;
        text-align: center;
        box-shadow: 3px 3px 3px rgba(0,0,0,.1);

        align-items: center;
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
        position: relative;
    }
    .box-cash:nth-child(3n+2){
        margin-right: 5%;
        margin-left: 5%;
        /* width: 40%;
        max-width: 665px; */
    }
    .box-cash-info{
        position:absolute;
        top:10px;
        right:12px;
    }
    .box-cash span{
        font-size: 20px;
        font-weight: 600;
        color: #004268;
    }
    .box-cash span a,
    .box-cash span a:hover{
        color: #004268;
    }
    .box-cash span div{
        float:left;
        text-align:center;
    }
    .box-cash span div:nth-child(1){
        margin-top:5px;
        width:30%;
    }
    .box-cash span div:nth-child(2){
        width:70%;
    }
    .box-cash span.green{
        color: #27ae60;/*#2ecc71;*/
    }
    .box-cash span.red{
        color: #e74c3c;
    }
    .box-cash .bold p{
        font-weight: bold;
    }
    .box-cash .box-cash-inside{
        display: grid;
        grid-template-columns: 1fr 1fr;
    }
    .box-cash .box-cash-inside div:nth-child(1){
        margin-right: 25px;
    }
    .box-cash .box-cash-inside div.flex{
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
<div class="box-cashes">
    <div class="box-cash">
        <div>
            <span><a href="<?=$this->Url->build(['action' => 'financeiro'])?>">
            <div>
                <?= $this->Html->image('icons/price.png', ["width" => "45px", 'heigth' => "45px"]) ?>
            </div>
            <div>
                Relatório Financeiro
            </div>
            </a></span>
        </div>
    </div>
    <div class="box-cash">
        <div>
            <span><a href="<?=$this->Url->build(['action' => 'cotation'])?>">
            <div>
                <?= $this->Html->image('icons/discount.png', ["width" => "45px", 'heigth' => "45px"]) ?>
            </div>
            <div>
                Relatório de Cotações
            </div>
            </a></span>
        </div>
    </div>
</div>


    <!-- Bootstrap 3.3.7 -->
    <script src="<?= $this->Url->build('bower_components/bootstrap/dist/js/bootstrap.min.js') ?>"></script>
    <!-- Sweetalert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>