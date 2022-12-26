
<?php include 'sidebar.php'; ?>
<?php $dataselect = mysqli_query($conn, "SELECT * FROM produk");
$jsArray = "var nama_produk = new Array();";
$jsArray1 = "var harga_jual = new Array();";
$jsArray2 = "var harga_modal = new Array();";  
 ?>
<!-- isinya -->
<form method="post">
<div class="row">

  <div class="col-sm-4 col-md-4 col-lg-3 mb-3">
    <label class="small text-muted mb-1">Kode Produk</label>
    <div class="position-relative">
    <input type="text" name="Ckdproduk" class="form-control form-control-sm" list="datalist1" onchange="changeValue(this.value)" required autofocus>
    <datalist id="datalist1">
        <?php if(mysqli_num_rows($dataselect)) {?>
            <?php while($row_brg= mysqli_fetch_array($dataselect)) {?>
                <option value="<?php echo $row_brg["kode_produk"]?>"> <?php echo $row_brg["kode_produk"]?>
            <?php $jsArray .= "nama_produk['" . $row_brg['kode_produk'] . "'] = {nama_produk:'" . addslashes($row_brg['nama_produk']) . "'};";
            $jsArray1 .= "harga_jual['" . $row_brg['kode_produk'] . "'] = {harga_jual:'" . addslashes($row_brg['harga_jual']) . "'};";
            $jsArray2 .= "harga_modal['" . $row_brg['kode_produk'] . "'] = {harga_modal:'" . addslashes($row_brg['harga_modal']) . "'};"; } ?>
        <?php } ?>
    </datalist>
    <span class="position-absolute icon-qr"><i class="fas fa-qrcode text-muted"></i></span>
    </div>
  </div>
  <div class="col-sm-4 col-md-4 col-lg-3 mb-3">
    <label class="small text-muted mb-1">Nama Produk</label>
    <input type="text" name="Cnproduk" id="nama_produk" class="form-control form-control-sm bg-light" readonly>
    <input type="hidden" name="harga_modal" id="harga_modal">
  </div>
  <div class="col-8 col-sm-4 col-md-4 col-lg-2 mb-3">
    <label class="small text-muted mb-1">Harga</label>
    <input type="number" name="Charga" placeholder="0" id="harga_jual" onchange="InputSub()"
     class="form-control form-control-sm bg-light" readonly>
  </div>
  <div class="col-4 col-sm-4 col-md-4 col-lg-1 mb-3">
    <label class="small text-muted mb-1">Qty</label>
    <input type="number" name="Cqty" id="Iqty" onchange="InputSub()" placeholder="0" class="form-control form-control-sm" required>
  </div>
  <div class="col-sm-8 col-md-8 col-lg-3 mb-3">
    <label class="small text-muted mb-1">Subtotal</label>
    <div class="input-group">
      <input type="number" name="Csubs" placeholder="0" id="Isubtotal" onchange="InputSub()" class="form-control form-control-sm bg-light mr-2" readonly>
    <div class="input-group-append">
      <button type="reset" class="btn btn-danger btn-sm mr-2">Reset</button>
      <button type="submit" name="InputCart" class="btn btn-primary btn-sm">Simpan</button>
    </div>
  </div>
  </div>
</div><!-- end row -->
</form>
<?php 
if(isset($_POST['InputCart']))
{
    $Input1 = htmlspecialchars($_POST['Ckdproduk']);
    $Input2 = htmlspecialchars($_POST['Cnproduk']);
    $Input3 = htmlspecialchars($_POST['Charga']);
    $Input5 = htmlspecialchars($_POST['Csubs']);
    $hrg_m = htmlspecialchars($_POST['harga_modal']);

    $cekDulu = mysqli_query($conn,"SELECT * FROM cart ");
    $liat = mysqli_num_rows($cekDulu);
    $f = mysqli_fetch_array($cekDulu);
    $inv_c = $f['invoice'];
    $ii = htmlspecialchars($_POST['Cqty']);

    if($liat>0){
      $cekbrg = mysqli_query($conn,"SELECT * FROM cart WHERE kode_produk='$Input1' and invoice='$inv_c'");
      $liatlg = mysqli_num_rows($cekbrg);
      $brpbanyak = mysqli_fetch_array($cekbrg);
      $jmlh = $brpbanyak['qty'];
      $jmlh1 = $brpbanyak['harga'];
      
      if($liatlg>0){
        $i = htmlspecialchars($_POST['Cqty']);
        $baru = $jmlh + $i;
        $baru1 = $jmlh1 * $baru;

        $updateaja = mysqli_query($conn,"UPDATE cart SET qty='$baru', subtotal='$baru1' WHERE invoice='$inv_c' and kode_produk='$Input1'");
        if($updateaja){
           echo '<script>window.location="index.php"</script>';
        } else {
           echo '<script>window.location="index.php"</script>';
        }
      } else {
      $tambahdata = mysqli_query($conn,"INSERT INTO cart (invoice,kode_produk,nama_produk,harga,harga_modal,qty,subtotal)
       values('$inv_c','$Input1','$Input2','$Input3','$hrg_m','$ii','$Input5')");
      if ($tambahdata){
          echo '<script>window.location="index.php"</script>';
      } else { echo '<script>window.location="index.php"</script>';
      }
      };
} else {
  
  $queryStar = mysqli_query($conn, "SELECT max(invoice) as kodeTerbesar FROM inv");
  $data = mysqli_fetch_array($queryStar);
  $kodeInfo = $data['kodeTerbesar'];
  $urutan = (int) substr($kodeInfo, 8, 2);
  $urutan++;
  $huruf = "AD";
  $oi = $huruf . date("jnyGi") . sprintf("%02s", $urutan);
    
    $bikincart = mysqli_query($conn,"INSERT INTO inv (invoice,pembayaran,kembalian,status) values('$oi','','','proses')");
    if($bikincart){
      $tambahuser = mysqli_query($conn,"INSERT INTO cart (invoice,kode_produk,nama_produk,harga,harga_modal,qty,subtotal)
      values('$oi','$Input1','$Input2','$Input3','$hrg_m','$ii','$Input5')");
      if ($tambahuser){
        echo '<script>window.location="index.php"</script>';
      } else { echo '<script>window.location="index.php"</script>';
      }
    } else {
      
    }
}
};
$DataInv = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM cart LIMIT 1"));
$noinv = $DataInv['invoice'];
?>
<div class="bg-purple p-2 text-white" style="border-radius:0.25rem;">
  <h5 class="mb-0">No. Invoice : <?php echo $noinv ?></h5>
</div>
<table class="table table-striped table-sm table-bordered dt-responsive nowrap print-none" id="cart" width="100%">
<thead>
  <tr>
    <th>#</th>
    <th>Kode Produk</th>
    <th>Nama Produk</th>
    <th>Harga</th>
    <th>Qty</th>
    <th>Subtotal</th>
    <th>Opsi</th>
  </tr>
</thead>
<tbody>
<?php 
$no = 1;
$tot_bayar = 0;
$data_cart = mysqli_query($conn,"SELECT * FROM cart");
while($d = mysqli_fetch_array($data_cart)){
    ?>
  <tr>
    <td><?php echo $no++; ?></td>
    <td><?php echo $d['kode_produk']; ?></td>
    <td><?php echo $d['nama_produk']; ?></td>
    <td>Rp.<?php echo ribuan($d['harga']); ?></td>
    <td><?php echo $d['qty']; ?></td>
    <td>Rp.<?php echo ribuan($d['subtotal']); ?></td>
    <td><a class="btn btn-danger btn-xs" href="?hapus=<?php echo $d['idcart']; ?>">
        <i class="fas fa-trash-alt fa-xs mr-1"></i>Hapus</a>
      </td>
  </tr>
  <?php } ?>
</tbody>
</table>
<?php 
if(!empty($_GET['hapus'])){
  $idcart = $_GET['hapus'];
  $hapus_data_Cart = mysqli_query($conn, "DELETE FROM cart WHERE idcart='$idcart'");
      if($hapus_data_Cart){
          echo '<script>history.go(-1);</script>';
      } else {
          echo '<script>alert("Gagal Hapus Data keranjang");history.go(-1);</script>';
      }
};
if(!empty($_GET['hapusAll'])){
  $noinvoicenya = $_GET['hapusAll'];
  $hapus_data_Cart_all = mysqli_query($conn, "DELETE FROM cart WHERE invoice='$noinvoicenya'");
  $hapus_data_Cart_all1 = mysqli_query($conn, "DELETE FROM inv WHERE invoice='$noinvoicenya'");
      if($hapus_data_Cart_all&&$hapus_data_Cart_all1){
          echo '<script>history.go(-1);</script>';
      } else {
          echo '<script>alert("Gagal Hapus Data keranjang");history.go(-1);</script>';
      }
};
    $itungtrans = mysqli_query($conn,"SELECT SUM(subtotal) as jumlahtrans FROM cart");
	  $itungtrans2 = mysqli_fetch_assoc($itungtrans);
	  $itungtrans3 = $itungtrans2['jumlahtrans'];
  ?>
<div class="bg-light p-3" style="border-radius:0.25rem;">
  <div class="row gy-3 align-items-center row-home">

    <div class="col-md-8 col-lg-6 mb-2">
    <form method="post">
      <input type="hidden" id="totalCart" value="<?php echo $itungtrans3; ?>">
      <div class="row">
        <label for="pembayaran" class="col-4 col-sm-4 col-md-4 col-lg-3 col-form-label col-form-label-sm mb-2">Pembayaran</label>
        <div class="col-8 col-sm-8 col-md-8 col-lg-9 mb-2">
          <input type="text" name="pembayaran" onchange="procesBayar()" class="form-control form-control-sm" id="pembayaran" placeholder="0" required>
        </div>
        <label for="kembalian" class="col-4 col-sm-4 col-md-4 col-lg-3 col-form-label col-form-label-sm mb-2">Kembalian</label>
        <div class="col-8 col-sm-8 col-md-8 col-lg-9 mb-2">
          <input type="text" class="form-control form-control-sm bg-light" id="kembalian" placeholder="0" readonly>
          <input type="hidden" name="kembalian" id="kembalian1">
        </div>
        <div class="col-sm-12 text-right">
      <div class="d-block d-sm-block d-md-none d-lg-none py-1"></div>
       <?php 
       $on = mysqli_query($conn,"SELECT * FROM cart");
       $x1 = mysqli_num_rows($on);
       if($x1>0){
        ?>
        <a href="?hapusAll=<?php echo $noinv ?>" onclick="javascript:return confirm('Anda yakin ingin menghapus semua data keranjang ?');"
       class="btn btn-danger btn-sm px-3 mr-2"><i class="fa fa-trash-alt mr-1"></i>Hapus Semua</a>
        <button type="submit" name="import" class="btn btn-primary btn-sm px-3">
        <i class="fa fa-check mr-1"></i>Simpan</button>
       <?php } else { ?>
          <button class="btn btn-danger btn-sm px-3 mr-2" disabled>
          <i class="fa fa-trash-alt mr-1"></i>Hapus Semua</button>
          <button class="btn btn-primary btn-sm px-3" disabled>
          <i class="fa fa-check mr-1"></i>Simpan</button>
      <?php  } ?>
    </div>
      </div>
      </form>
    </div>

    <div class="col-md-4 col-lg-6 mb-2 text-primary text-right">
      <p class="small text-muted mb-0">Total Item</p>
      <h3 class="mb-0" style="font-weight:600;">Rp. <?php echo ribuan($itungtrans3) ?></h3>
    </div>
    
  </div>
</div>
<!-- end isinya -->

</div><!-- end container-fluid" -->
  </main><!-- end page-content" -->
</div><!-- end page-wrapper -->
<?php 
if(isset($_POST['import']))
{
    $Ipembayaran = htmlspecialchars($_POST['pembayaran']);
    $Ikembalian = htmlspecialchars($_POST['kembalian']);

    $UpdCart = mysqli_query($conn,"UPDATE inv SET
      pembayaran='$Ipembayaran',kembalian='$Ikembalian',status='selesai' WHERE invoice='$noinv'") 
     or die (mysqli_connect_error()); 

     $UpdLap = mysqli_query($conn, "INSERT INTO laporan (invoice,kode_produk,nama_produk,harga,harga_modal,qty,subtotal)
     SELECT invoice,kode_produk,nama_produk,harga,harga_modal,qty,subtotal FROM cart") or die (mysqli_connect_error());

    $DelCart = mysqli_query($conn,"DELETE FROM cart") or die (mysqli_connect_error());
    
    if($UpdCart&&$UpdLap&&$DelCart){
        echo '<script>window.location="invoice.php?detail='.$noinv.'"</script>';
    } else {
      echo '<script>alert("Gagal Di Simpan");history.go(-1);</script>';
    }
};
?>
<!-- Modal Exit -->
<div class="modal fade" id="Exit" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content border-0">
      <div class="modal-body text-center">
      <i class="fas fa-exclamation-triangle fa-4x text-warning mb-3"></i>
        <h3 class="mb-4">Apakah anda yakin ingin keluar ?</h3>
        <button type="button" class="btn btn-secondary px-4 mr-2" data-dismiss="modal">Batal</button>
        <a href="logout.php" class="btn btn-primary px-4">Keluar</a>
    </div>
  </div>
</div>
<!-- end Modal Exit -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="assets/js/sidebar.js"></script>
    <script src="assets/vendor/datatables/jquery-3.5.1.js"></script>
    <script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="assets/vendor/datatables/dataTables.responsive.min.js"></script>
    <script src="assets/vendor/datatables/responsive.bootstrap4.min.js"></script>
    <script type="text/javascript">
        $('#cart').dataTable({searching: false, paging: false, info: false});
    </script>
    <script type="text/javascript">
      <?php echo $jsArray,$jsArray1,$jsArray2; ?>
        function changeValue(kode_produk) {
          document.getElementById("nama_produk").value = nama_produk[kode_produk].nama_produk;
          document.getElementById("harga_jual").value = harga_jual[kode_produk].harga_jual;
          document.getElementById("harga_modal").value = harga_modal[kode_produk].harga_modal;
        };
        function InputSub() {
        var harga_jual =  parseInt(document.getElementById('harga_jual').value);
        var jumlah_beli =  parseInt(document.getElementById('Iqty').value);
        var jumlah_harga = harga_jual * jumlah_beli;
          document.getElementById('Isubtotal').value = jumlah_harga;
      };
      function procesBayar() {
      var harga_Cart =  parseInt(document.getElementById('totalCart').value);
      var pembayaran_Cart =  parseInt(document.getElementById('pembayaran').value);
      var kembali_Cart = pembayaran_Cart - harga_Cart;
  
      var	number_string = kembali_Cart.toString(),
          sisa 	= number_string.length % 3,
          rupiah1 	= number_string.substr(0, sisa),
          ribuan1 	= number_string.substr(sisa).match(/\d{3}/gi);
            
        if (ribuan1) {
          separator1 = sisa ? '.' : '';
          rupiah1 += separator1 + ribuan1.join('.');
        }
        
        document.getElementById('kembalian').value = rupiah1;
        document.getElementById('kembalian1').value = kembali_Cart;
      };
  </script>
</body>
</html>