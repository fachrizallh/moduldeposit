{if $tampiltidak==1}
<div class="row">
	<div class="col-xs-12 col-md-6">
        <p class="payment_module">
			<a style="background:url('{$modules_dir|escape:'html':'UTF-8'}moduldeposit/views/img/deposit.png') no-repeat scroll #FBFBFB; background-position: center; background-size:100%; " href="{$link->getModuleLink('moduldeposit', 'payment')|escape:'html'}" class="moduldeposit">
                <font size='4'>{l s='Pembayaran dengan Deposit' mod='moduldeposit'}</font><br>
					 <font size='2'>{l s='Sisa Saldo Deposit Anda: '}Rp {$saldo_sekarang|number_format:2:",":"."}</font>
            </a>
        </p>
    </div>
</div>
{else if}
<div class="row">
	<div class="col-xs-12 col-md-6">
        <p class="payment_module">
			<a style="background:url('{$modules_dir|escape:'html':'UTF-8'}moduldeposit/views/img/deposit.png') no-repeat scroll #FBFBFB; background-position: center; background-size:100%; " href="javascript: void(0)" class="moduldeposit">
                <font size='4'>{l s='Pembayaran dengan Deposit' mod='moduldeposit'}</font><br>
					 <font size='2'>{l s='Sisa Saldo Deposit Anda: '}Rp {$saldo_sekarang|number_format:2:",":"."}</font>
            </a>
        </p>
    </div>
</div>
{/if}