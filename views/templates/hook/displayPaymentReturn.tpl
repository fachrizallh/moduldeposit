<div class="box">
	<p class="cheque-indent">
		<strong class="dark">{l s='Pesanan Anda di %s telah selesai.' sprintf=$shop_name mod='ModulDeposit'}</strong>
	</p><br>
	<p>
        {l s='Transfer pembayaran Anda dengan' mod='ModulDeposit'}<br>
        - {l s='Jumlah' mod='ModulDeposit'} <span class="price"> <strong>{$total_to_pay}</strong></span><br>
        - {l s='Metode Pembayaran' mod='ModulDeposit'}  <strong>{l s='Pembayaran Deposit'}</strong><br>
    </p><br>

	<p><strong>{l s='Jangan lupa lakukan "Konfirmasi Pembayaran" agar kami segera memproses pesanan Anda' mod='ModulDeposit'}</strong></p><br>
	<p>{l s='Jika Anda memiliki pertanyaan, komentar, silahkan hubungi kami.' mod='ModulDeposit'}</p>
</div>