<style>    #deposit-form label.error{        font:12px Arial, Helvetica, Tahoma,sans-serif;        color:#ED7476;        margin-left:5px;        display: inline-block;    }
    #deposit-form input.error{        border:1px solid #ED7476;    }
    #deposit-form label.valid{        margin-left:5px;        display: inline-block;    }
    #deposit-form input.valid{        border:1px solid #33cc00;    }</style><script>    function isNumberKey(evt){        var charCode = (evt.which) ? evt.which : event.keyCode        if (charCode > 31 && (charCode < 48 || charCode > 57))            return false;        return true;    }    $(function(){        $('#deposit-form').validate({            //ignore: null,            ignore: 'input[type="hidden"]',            rules: {                nama_bank:{                    required:true,                },                nominal_tambah:{                    required:true,						  max:500000,						  min:100000,                }            },            messages: {                nama_bank: {                    required: "Tolong pilih nama bank",                },                nominal_tambah: {                    required: "Tolong isi jumlah saldo yang ingin ditambah, 100000 - 500000",                    max: "Saldo maksimal yang bisa dideposit 500000",						  min: "Saldo minimal yang bisa dideposit 100000",                }            }        });    });</script>{capture name=path}    <a href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}">        {l s='My account'}    </a>    <span class="navigation-pipe">{$navigationPipe}</span>    <span class="navigation_page">{l s='Deposit Pembayaran'}</span>{/capture}<div class="box box-small clearfix">    <div class="rte col-xs-12 col-sm-6">        <form action="{$link->getModuleLink('moduldeposit', 'prosesdeposit')}" method="POST" id="deposit-form">				<div class="form-group">                <label for="nominal_tambah" class="required">Nominal Deposit</label>                <input type="text" name="nominal_tambah" id="nominal_tambah" class="form-control" onkeypress="return isNumberKey(event)" placeholder="Masukan jumlah yang ingin di depositkan (antara 100000 - 500000). Contoh: 100000"/>            </div>				<div class="form-group">					<label for="nama_bank" class="required">Nama Bank</label></br>					<select name="nama_bank" id="nama_bank">						{foreach $bank_tersedia as $banksss}							<option value='{$banksss.nama_bank}'>{$banksss.nama_bank}</option>						{/foreach}					</select>				</div>            <div class="submit">                <button type="submit" name="mymod_pc_submit_deposit" class="button btn btn-default button-medium">                    <span>Send<i class="icon-chevron-right right"></i></span>                </button>            </div>        </form>    </div></div>