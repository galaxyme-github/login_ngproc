<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <?php if ($userRole == 0): ?>
            <!-- ====== ADMIN ===== -->
                <li>
                    <a href="<?= $this->Url->build('/') ?>" class="logo">
                        <!-- logo for regular state and mobile devices -->
                        <span class="logo-lg"><?= $this->Html->image('icons/ngproc-logo.png') ?></span>
                        <!-- <div class="linha-ngproc"></div> -->
                    </a>
                </li>
                <li>
                    <a href="<?=$this->Url->build(['controller' => 'Clients', 'action' => 'index'])?>">
                        <div class="sidebar-icones">
                            <div><?=$this->Html->image('icons/user' . ($controllerName == 'Clients' ? '-active' : '') . '.png')?></div>
                            <div class="sidebar-icones-legenda">Clientes</div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="<?=$this->Url->build(['controller' => 'Partners', 'action' => 'index'])?>">
                        <div class="sidebar-icones">
                            <div><?=$this->Html->image('icons/handshake' . ($controllerName == 'Partners' ? '-active' : '') . '.png')?></div>
                            <div class="sidebar-icones-legenda">Parceiros</div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="<?=$this->Url->build(['controller' => 'Providers', 'action' => 'index'])?>">
                        <div class="sidebar-icones">
                            <div><?=$this->Html->image('icons/shop.png')?></div>
                            <div class="sidebar-icones-legenda">Fornecedores</div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <div class="sidebar-icones">
                            <div><?=$this->Html->image('icons/key-silhouette-security-tool-interface-symbol-of-password.png')?></div>
                            <div class="sidebar-icones-legenda">Acesso</div>
                        </div>
                        <div style="position:absolute; z-index:2; top:25px;"><?=$this->Html->image('icons/embreve2.png')?></div>
                    </a>
                </li>
                <li>
                    <a href="<?= $this->Url->build(['controller' => 'Relatorios', 'action' => 'index'])?>">
                        <div class="sidebar-icones">
                            <div><?=$this->Html->image('icons/file.png')?></div>
                            <div class="sidebar-icones-legenda">Relatórios</div>
                        </div>
                        <!-- <div style="position:absolute; z-index:2; top:25px;"><?=$this->Html->image('icons/embreve2.png')?></div> -->
                    </a>
                </li>
                <li>
                    <a href="<?=$this->Url->build(['prefix' => false, 'controller' => 'Users', 'action' => 'logout'])?>">
                        <div class="sidebar-icones">
                            <div><?=$this->Html->image('icons/logout (1).png')?></div>
                            <div class="sidebar-icones-legenda">Sair</div>
                        </div>
                    </a>
                </li>

            <?php elseif ($userRole == 2): ?>
            <!-- ====== PARCEIROS ===== -->
                <li>
                    <a href="<?= $this->Url->build('/') ?>" class="logo">
                        <!-- logo for regular state and mobile devices -->
                        <span class="logo-lg"><?= $this->Html->image('icons/ngproc-logo.png') ?></span>
                        <!-- <div class="linha-ngproc"></div> -->
                    </a>
                </li>
                <li>
                    <a href="<?=$this->Url->build(['prefix' => 'partner', 'controller' => 'Quotation', 'action' => 'index'])?>">
                        <div class="sidebar-icones">
                            <div><?=$this->Html->image('icons/list (1)' . ($controllerName == 'Quotation' ? '-active' : '') . '.png')?></div>
                            <div class="sidebar-icones-legenda">Cotações</div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="<?=$this->Url->build(['prefix' => 'partner', 'controller' => 'Sent', 'action' => 'index'])?>">
                        <div class="sidebar-icones">
                            <div><?=$this->Html->image('icons/paper-plane.png')?></div>
                            <div class="sidebar-icones-legenda">Enviados</div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="<?= $this->Url->build(['prefix' => 'partner', 'controller' => 'Relatorios', 'action' => 'index'])?>">
                        <div class="sidebar-icones">
                            <div><?=$this->Html->image('icons/file.png')?></div>
                            <div class="sidebar-icones-legenda">Relatório</div>
                        </div>
                        <!-- <div style="position:absolute; z-index:2; top:25px;"><?=$this->Html->image('icons/embreve2.png')?></div> -->
                    </a>
                </li>
                <li>
                    <a href="<?=$this->Url->build(['prefix' => 'partner', 'controller' => 'Profile', 'action' => 'index'])?>">
                        <div class="sidebar-icones">
                            <div><?=$this->Html->image('icons/user' . ($controllerName == 'Profile' ? '-active' : '') . '.png')?></div>
                            <div class="sidebar-icones-legenda">Perfil</div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="<?=$this->Url->build(['prefix' => false, 'controller' => 'Users', 'action' => 'logout'])?>">
                        <div class="sidebar-icones">
                            <div><?=$this->Html->image('icons/logout (1).png')?></div>
                            <div class="sidebar-icones-legenda">Sair</div>
                        </div>
                    </a>
                </li>
                <?php elseif ($userRole == 1): ?>
                <!-- ====== CLIENTES ===== -->
                <li>
                    <a href="<?= $this->Url->build('/') ?>" class="logo">
                        <!-- logo for regular state and mobile devices -->
                        <span class="logo-lg"><?= $this->Html->image('icons/ngproc-logo.png') ?></span>
                        <!-- <div class="linha-ngproc"></div> -->
                    </a>
                </li>
                <li>
                    <a href="<?=$this->Url->build(['prefix' => 'client', 'controller' => 'Quotation', 'action' => 'index'])?>">
                        <div class="sidebar-icones">
                            <div><?=$this->Html->image('icons/list (1)' . ($controllerName == 'Quotation' ? '-active' : '') . '.png')?></div>
                            <div class="sidebar-icones-legenda">Cotações</div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="#" style="position:relative">
                        <div class="sidebar-icones">
                            <div><?=$this->Html->image('icons/shopping-cart (3).png')?>
                            <!--<div class="image-size"></div>-->
                        </div>
                            <div class="sidebar-icones-legenda">Compras</div>
                        </div>
                        <div style="position:absolute; z-index:2; top:25px;"><?=$this->Html->image('icons/embreve2.png')?></div>
                    </a>
                </li>
                <li>
                    <a href="<?= $this->Url->build(['prefix' => 'client', 'controller' => 'Relatorios', 'action' => 'index'])?>">
                        <div class="sidebar-icones">
                            <div><?=$this->Html->image('icons/file.png')?></div>
                            <div class="sidebar-icones-legenda">Relatório</div>
                        </div>
                        <!-- <div style="position:absolute; z-index:2; top:25px;"><?=$this->Html->image('icons/embreve2.png')?></div> -->
                    </a>
                </li>
                <li>
                    <a href="<?=$this->Url->build(['prefix' => 'client', 'controller' => 'Profile', 'action' => 'index'])?>">
                        <div class="sidebar-icones">
                            <div><?=$this->Html->image('icons/user' . ($controllerName == 'Profile' ? '-active' : '') . '.png')?></div>
                            <div class="sidebar-icones-legenda">Perfil</div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="<?=$this->Url->build(['prefix' => false, 'controller' => 'Users', 'action' => 'logout'])?>">
                        <div class="sidebar-icones">
                            <div><?=$this->Html->image('icons/logout (1).png')?></div>
                            <div class="sidebar-icones-legenda">Sair</div>
                        </div>
                    </a>
                </li>
                <?php endif;?>
        </ul>
    </section>

    <?php if ($userRole == 1 || $userRole == 2): ?>
    <?php if($userRole == 1) $prefix = "client";?>
    <?php if($userRole == 2) $prefix = "partner";?>
    <div class="icon-corporate">
      <!-- <a href="<?=$this->Url->build(['prefix' =>  $prefix, 'controller' => 'Corporate', 'action' => 'index'])?>" style="position:relative; text-decoration:none">-->
      <a href="">
            <div class="sidebar-icones div-corporate" style="border:none">
                <div><?=$this->Html->image('icons/corporate.png')?></div>
                <div class="sidebar-icones-legenda">Corporate</div>
            </div>
            <div style="position:absolute; z-index:2; top:8px;"><?=$this->Html->image('icons/embreve2.png')?></div>
        </a>
    </div>
        <?php endif;?>
    <!-- /.sidebar -->
</aside>