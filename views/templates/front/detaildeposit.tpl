{capture name=path}
    <a href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}">
        {l s='My account'}
    </a>
    <span class="navigation-pipe">{$navigationPipe}</span>
    <span class="navigation_page">{l s='Pembayaran Deposit'}</span>
{/capture}
{include file="$tpl_dir./errors.tpl"}
<h1 class="page-heading bottom-indent">{l s='Pembayaran Deposit'}</h1>
<p class="info-title">{l s='Berikut ini adalah daftar pesanan yang telah Anda pesan sebelumnya'}</p>
{if isset($konfirmasi)}
    <div class="alert alert-success">Konfirmasi Diterima</div>
{/if}
<div class="block-center" id="block-history">
        <table id="order-list" class="table table-bordered footab">
            <thead>
					<tr>
						 <th class="first_item" data-sort-ignore="true">{l s='Nama'}</th>
						 <th data-hide="phone" class="item">{l s='Saldo Anda'}</th>
						 <th data-hide="phone" class="item"></th>
					</tr>
            </thead>
            <tbody>
                <tr class="{if $smarty.foreach.myLoop.first}first_item{elseif $smarty.foreach.myLoop.last}last_item{else}item{/if} {if $smarty.foreach.myLoop.index % 2}alternate_item{/if}">
                    <td class="history_link bold">
                            {$nama}
                    </td>
                    <td class="history_price"">
							<span class="price">
								{l s='Rp '}{$saldo}{l s=',-'}
							</span>
                    </td>
                    <td class="history_detail">
							<a class="btn btn-default button button-small" href="{$link->getModuleLink('moduldeposit', 'FormDeposit')|escape:'html':'UTF-8'}">
							<span>
								{l s='Tambah Saldo Pembayaran Deposit'}<i class="icon-chevron-right right"></i>
							</span>
							</a>
                    </td>
                </tr>
            </tbody>
        </table>
        <div id="block-order-detail" class="unvisible">&nbsp;</div>
</div>
<ul class="footer_links clearfix">
    <li>
        <a class="btn btn-default button button-small" href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}">
			<span>
				<i class="icon-chevron-left"></i> {l s='Kembali ke akun Anda'}
			</span>
        </a>
    </li>
    <li>
        <a class="btn btn-default button button-small" href="{$base_dir}">
            <span><i class="icon-chevron-left"></i> {l s='Beranda'}</span>
        </a>
    </li>
</ul>