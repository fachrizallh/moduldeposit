{capture name=path}
    {l s='Pembayaran Deposit'}
{/capture}

<h1 class="page-heading">
{l s='Order summary' mod='ModulDeposit'}
</h1>

{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}

{if $nb_products <= 0}
    <p class="alert alert-warning">
        {l s='Keranjang belanja Anda kosong.' mod='ModulDeposit'}
    </p>
{else}
    <form action="{$link->getModuleLink('moduldeposit', 'validation', [], true)|escape:'html'}" method="post">
	<div class="box cheque-box">
		<h3 class="page-subheading">
            {l s='Pembayaran Deposit'}
		</h3>
		<p class="cheque-indent">
			<strong class="dark">
                {l s='Anda telah memilih pembayaran dengan ' mod='ModulDeposit'}{l s='Pembayaran Deposit'}{l s='. Berikut ini adalah ringkasan singkat pesanan Anda:' mod='ModulDeposit'}
			</strong>
		</p>
		<p>
			- {l s='Jumlah total pesanan Anda adalah' mod='ModulDeposit'}
			<span id="amount" class="price">{displayPrice price=$total_amount}</span>
            {if $use_taxes == 1}
                {l s='(tax incl.)' mod='ModulDeposit'}
            {/if}
		</p>
		<p>
			- {l s='Detail informasi pembayaran akan ditampilkan pada halaman selanjutnya.' mod='ModulDeposit'}
			<br />
			- {l s='Mohon konfirmasi pesanan Anda dengan menekan "Konfirmasi Pesanan."' mod='ModulDeposit'}.
		</p>
	</div><!-- .cheque-box -->

	<p class="cart_navigation clearfix" id="cart_navigation">
		<a
				class="button-exclusive btn btn-default"
				href="{$link->getPageLink('order', true, NULL, "step=3")|escape:'html':'UTF-8'}">
			<i class="icon-chevron-left"></i>{l s='Metode pembayaran lainnya' mod='ModulDeposit'}
		</a>
		<button
				class="button btn btn-default button-medium"
				type="submit">
			<span>{l s='Konfirmasi Pesanan' mod='ModulDeposit'}<i class="icon-chevron-right right"></i></span>
		</button>
	</p>
    </form>
{/if}







