CREATE TABLE IF NOT EXISTS PREFIX_pembayaran_deposit (
id_pembayaran_deposit int(11) NOT NULL AUTO_INCREMENT,
id_pembeli int(11) NOT NULL,
nama_pembeli varchar(40) NOT NULL,
nama_bank varchar(20) NOT NULL,
nominal_tambah int(11) NOT NULL,
saldo_sekarang int(11) NOT NULL,
tanggal DATETIME NOT NULL,
active tinyint(1) NOT NULL,
PRIMARY KEY (id_pembayaran_deposit)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 