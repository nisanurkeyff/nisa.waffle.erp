<?
	require_once ($_SERVER['DOCUMENT_ROOT'] . '/class/config.php');

	$result = $cPaytr->fncPaytrOdeme();
	var_dump2($result);die;

	/*
	$data = array();
	$sql = "SELECT 
	            S.*,
	            YEAR(S.TARIH) AS YIL
	        FROM STOK AS S
	            LEFT JOIN KATEGORI AS K ON K.ID = S.KATEGORI_ID
	            LEFT JOIN STOK_RESIM AS SR ON SR.STOK_ID = S.ID AND SR.VITRIN = 1
	            LEFT JOIN KULLANICI AS KU ON KU.ID = S.KAYIT_YAPAN_ID
	        WHERE 1";
	$rows = DB::get($sql, $data);

	$say = 0;
	foreach ($rows as $key => $row) {

	    $arrContextOptions = array(
	        "ssl" => array(
	            "verify_peer" => false,
	            "verify_peer_name" => false,
	        ),
	    );

	    $url = "https://inovastil.com/upload/product/" . $row->RESIM_URL;
	    $result = file_get_contents($url, false, stream_context_create($arrContextOptions));

	    if ($result !== false) {
	        $yol = "img/stok/" . $row->ID . "/" . $row->YIL . "/";
	        if (!is_dir($yol)) {
	            mkdir($yol, 0777, true);
	        }

	        $dosya_uzantisi = pathinfo($row->RESIM_URL, PATHINFO_EXTENSION);

	        list($usec, $sec) = explode(' ', microtime());
	        $base64 = str_replace('.', '', (((float)$usec + (float)$sec)));
	        $base64 = str_pad($base64, 14, "0", STR_PAD_RIGHT);
	        $resim_adi = $base64 . '.' . $dosya_uzantisi;
	        $dosyaYolu = $yol . $resim_adi;

	        // Dosya kaydetme
	        if (file_put_contents($dosyaYolu, $result)) {
	            $data = array();
	            $sql = "INSERT INTO STOK_RESIM SET  STOK_ID         = :STOK_ID,
	                                                RESIM_ADI       = :RESIM_ADI,
	                                                RESIM_ADI_ILK   = :RESIM_ADI_ILK,
	                                                KAYIT_YAPAN_ID  = :KAYIT_YAPAN_ID";
	            $data[":STOK_ID"]         = $row->ID;
	            $data[':RESIM_ADI']       = $resim_adi;
	            $data[':RESIM_ADI_ILK']   = $row->RESIM_URL;
	            $data[":KAYIT_YAPAN_ID"]  = 1;
	            $id = DB::insert($sql, $data);

	            if ($id > 0) {
	                $say++;
	            }
	        }
	    }
	}
	*/
	echo $say;
	
