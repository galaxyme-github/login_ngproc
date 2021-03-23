<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Provider $provider
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Provider'), ['action' => 'edit', $provider->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Provider'), ['action' => 'delete', $provider->id], ['confirm' => __('Are you sure you want to delete # {0}?', $provider->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Providers'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Provider'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="providers view large-9 medium-8 columns content">
    <h3><?= h($provider->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($provider->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Cnpj') ?></th>
            <td><?= h($provider->cnpj) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Site') ?></th>
            <td><?= h($provider->site) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Telephone') ?></th>
            <td><?= h($provider->telephone) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Address Zipcode') ?></th>
            <td><?= h($provider->address_zipcode) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Address Street') ?></th>
            <td><?= h($provider->address_street) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Address Number') ?></th>
            <td><?= h($provider->address_number) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Address Complement') ?></th>
            <td><?= h($provider->address_complement) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Address Neighborhood') ?></th>
            <td><?= h($provider->address_neighborhood) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Address City') ?></th>
            <td><?= h($provider->address_city) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Address Uf') ?></th>
            <td><?= h($provider->address_uf) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($provider->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($provider->created) ?></td>
        </tr>
    </table>
</div>
