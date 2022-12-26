
<?php include 'sidebar.php'; ?>
<!-- isinya -->
<?php
$noinv = $_GET['detail'];
if(!empty($_GET['detail'])){
} else {
echo '<script>history.go(-1);</script>';
}; 
$DataInv = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM inv WHERE invoice='$noinv'"));
$Dbayar = $DataInv['pembayaran'];
$Dkembali = $DataInv['kembalian'];
$Datee = $DataInv['tgl_inv'];
?>
<h1 class="h3 mb-2">
Detail
<span class="float-right">
<a href="index.php" class="btn btn-danger btn-sm px-3 mr-1">Kembali</a>
<button type="button" class="btn btn-primary btn-sm px-3"  onclick="document.title='Invoice#<?php echo $noinv ?>';window.print()">
Cetak</button>
</span>
</h1>
<div class="bg-purple p-2 text-white" style="border-radius:0.25rem;">
<div class="row">
    <div class="col-lg-6"><h5 class="mb-0">No. Invoice : <?php echo $noinv ?></h5></div>
    <div class="col-lg-6"><h5 class="mb-0 date-inv">Tanggal : <?php echo $Datee ?></h5></div>
</div>
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
  </tr>
</thead>
<tbody>
<?php 
$no = 1;
$tot_bayar = 0;
$data_laporan = mysqli_query($conn,"SELECT * FROM laporan WHERE invoice='$noinv'");
while($d = mysqli_fetch_array($data_laporan)){
    ?>
  <tr>
    <td><?php echo $no++; ?></td>
    <td><?php echo $d['kode_produk']; ?></td>
    <td><?php echo $d['nama_produk']; ?></td>
    <td>Rp.<?php echo ribuan($d['harga']); ?></td>
    <td><?php echo $d['qty']; ?></td>
    <td>Rp.<?php echo ribuan($d['subtotal']); ?></td>
  </tr>
  <?php } ?>
</tbody>
</table>
<?php
$i4 = mysqli_fetch_assoc(mysqli_query($conn,"SELECT SUM(subtotal) as isub FROM laporan WHERE invoice='$noinv'"));
?>
<div class="row justify-content-end mt-1">

    <div class="col-sm-6 col-md-5 col-lg-4">
        <div class="bg-purple text-white p-2">
            <h6 class="mb-0">Total Item
                <span class="float-right">Rp.<?php echo ribuan($i4['isub']); ?></span>
            </h6>
        </div>
        <div class="bg-light p-2">
            <h6 class="mb-2">Pembayaran
            <span class="float-right">Rp.<?php echo ribuan($Dbayar); ?></span>
            </h6>
            <h6 class="mb-0">Kembalian
            <span class="float-right">Rp.<?php echo ribuan($Dkembali); ?></span>
            </h6>
        </div>
    </div>

</div>

<!-- data print -->
<section id="print">
<div class="d-none pt-5 px-5 print-show">
        <div class="text-center mb-5 pt-2">
            <h2 class="mb-3" style="font-size:60px;"><?php echo $toko ?></h2>
            <h2 class="mb-0"><?php echo $alamat ?></h2>
            <h2 class="mb-4">Telp : <?php echo $telepon ?></h2>
        </div>
            <h2 class="mb-1">Invoice : <?php echo $noinv ?>
          <span class="float-right">Kasir : <?php echo $username ?></span></h2>
            <h2 class="mb-1">Tanggal : <?php echo $Datee ?></h2>
    <div class="row">
        <div class="col-12 py-3 my-3 border-top border-bottom">
            <div class="row">
                <div class="col-5"><h2 class="mb-0 py-1" style="font-weight:700;">Description</h2></div>
                <div class="col-2"><h2 class="mb-0 py-1" style="font-weight:700;">Harga</h2></div>
                <div class="col-2"><h2 class="mb-0 py-1" style="font-weight:700;">Qty</h2></div>
                <div class="col-3"><h2 class="mb-0 py-1" style="font-weight:700;">Jumlah</h2></div>
            </div>
        </div>
        <?php 
        $no = 1;
        $dataprint = mysqli_query($conn,"SELECT * FROM laporan WHERE invoice='$noinv'");
        while($c = mysqli_fetch_array($dataprint)){
            ?>
        <div class="col-12">
            <div class="row">
                <div class="col-5"><h2 class="mb-0 py-1" style="font-weight:500;"><?php echo $c['nama_produk']; ?></h2></div>
                <div class="col-2"><h2 class="mb-0 py-1" style="font-weight:500;"><?php echo ribuan($c['harga']); ?></h2></div>
                <div class="col-2"><h2 class="mb-0 py-1" style="font-weight:500;"><?php echo ribuan($c['qty']); ?></h2></div>
                <div class="col-3"><h2 class="mb-0 py-1" style="font-weight:500;"><?php echo ribuan($c['subtotal']); ?></h2></div>
            </div>
        </div>
      <?php } ?>
      <div class="col-12 py-3 my-3 border-top">
            <div class="row justify-content-end">

                <div class="col-3 text-right border-bottom">
                  <h2 class="mb-1" style="font-weight:700;">Total <span class="ml-3">:</span></h2>
                  <h2 class="mb-1" style="font-weight:500;">Tunai <span class="ml-3">:</span></h2>
                  <h2 class="mb-1" style="font-weight:500;">Kembali <span class="ml-3">:</span></h2>
                </div>
                <div class="col-3 border-bottom">
                  <h2 class="mb-1" style="font-weight:700;"><?php echo ribuan($i4['isub']); ?></h2>
                  <h2 class="mb-1" style="font-weight:500;"><?php echo ribuan($Dbayar); ?></h2>
                  <h2 class="mb-1" style="font-weight:500;"><?php echo ribuan($Dkembali); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-12 text-center mt-5">
            <h2>* Terima Kasih Telah Berbelanja Di TOKO KAMI :) *</h2>
        </div>
    </div><!-- end row -->
</div><!-- end box print -->
</section>
<!-- end data print -->

<!-- end isinya -->
<?php include 'footer.php'; ?>
