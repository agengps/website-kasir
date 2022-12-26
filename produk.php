
<?php include 'sidebar.php'; ?>
<!-- isinya -->
    <h1 class="h3 mb-0">
        Data Produk
        <button class="btn btn-primary btn-sm border-0 float-right" type="button" data-toggle="modal" data-target="#TambahProduk">Tambah Produk</button>
    </h1>
<hr>
<table class="table table-striped table-sm table-bordered dt-responsive nowrap" id="table" width="100%">
<thead>
  <tr>
    <th>No</th>
    <th>Kode Produk</th>
    <th>Nama Produk</th>
    <th>Harga Modal</th>
    <th>Harga Jual</th>
    <th>Tgl Input</th>
    <th>Opsi</th>
  </tr>
</thead>
<tbody>
<?php 
    $no = 1;
    $data_barang = mysqli_query($conn,"SELECT * FROM produk ORDER BY idproduk ASC");
    while($d = mysqli_fetch_array($data_barang)){
        ?>
  <tr>
    <td><?php echo $no++; ?></td>
    <td><?php echo $d['kode_produk']; ?></td>
    <td><?php echo $d['nama_produk']; ?></td>
    <td>Rp.<?php echo ribuan($d['harga_modal']); ?></td>
    <td>Rp.<?php echo ribuan($d['harga_jual']); ?></td>
    <td><?php echo $d['tgl_input']; ?></td>
    <td>
        <button type="button" class="btn btn-primary btn-xs mr-1" data-toggle="modal" data-target="#EditProduk<?php echo $d['idproduk']; ?>">
        <i class="fas fa-pencil-alt fa-xs mr-1"></i>Edit
        </button>
        <a class="btn btn-danger btn-xs" href="?hapus=<?php echo $d['idproduk']; ?>">
        <i class="fas fa-trash-alt fa-xs mr-1"></i>Hapus</a>
    </td>
  </tr>
  <!-- Modal Tambah Produk -->
<div class="modal fade" id="EditProduk<?php echo $d['idproduk']; ?>" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content border-0">
        <form method="post">
      <div class="modal-header bg-purple">
        <h5 class="modal-title text-white">Edit Produk</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <label class="samll">Kode Produk :</label>
            <input type="hidden" name="idproduk" value="<?php echo $d['idproduk']; ?>">
            <input type="text" name="Edit_Kode_Produk" value="<?php echo $d['kode_produk']; ?>" class="form-control" required>
        </div>
        <div class="form-group">
            <label class="samll">Nama Produk :</label>
            <input type="text" name="Edit_Nama_Produk" value="<?php echo $d['nama_produk']; ?>" class="form-control" required>
        </div>
        <div class="form-group">
            <label class="samll">Harga Modal :</label>
            <input type="number" placeholder="0" name="Edit_Harga_Modal" value="<?php echo $d['harga_modal']; ?>" class="form-control" required>
        </div>
        <div class="form-group">
            <label class="samll">Harga Jual :</label>
            <input type="number" placeholder="0" name="Edit_Harga_Jual" value="<?php echo $d['harga_jual']; ?>" class="form-control" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary" name="SimpanEdit">Simpan</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- end Modal Produk -->
  <?php } ?>
</tbody>
</table>
<?php 
if(isset($_POST['TambahProduk']))
{
    $kodeproduk = htmlspecialchars($_POST['Tambah_Kode_Produk']);
    $namaproduk = htmlspecialchars($_POST['Tambah_Nama_Produk']);
    $harga_modal = htmlspecialchars($_POST['Tambah_Harga_Modal']);
    $harga_jual = htmlspecialchars($_POST['Tambah_Harga_Jual']);

    $cekkode = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM produk WHERE kode_produk='$kodeproduk'"));
    if($cekkode > 0) {
        echo '<script>alert("Maaf! kode produk sudah ada");history.go(-1);</script>';
    } else {
    $InputProduk = mysqli_query($conn,"INSERT INTO produk (kode_produk,nama_produk,harga_modal,harga_jual)
     values ('$kodeproduk','$namaproduk','$harga_modal','$harga_jual')");
    if ($InputProduk){
        echo '<script>history.go(-1);</script>';
    } else {
        echo '<script>alert("Gagal Menambahkan Data");history.go(-1);</script>';
    }
}
};
if(isset($_POST['SimpanEdit'])){
    $idproduk1 = htmlspecialchars($_POST['idproduk']);
    $kodeproduk1 = htmlspecialchars($_POST['Edit_Kode_Produk']);
    $namaproduk1 = htmlspecialchars($_POST['Edit_Nama_Produk']);
    $harga_modal1 = htmlspecialchars($_POST['Edit_Harga_Modal']);
    $harga_jual1 = htmlspecialchars($_POST['Edit_Harga_Jual']);

    $CariProduk = mysqli_query($conn,"SELECT * FROM produk WHERE kode_produk='$kodeproduk1' and idproduk!='$idproduk1'");
    $HasilData = mysqli_num_rows($CariProduk);

    if($HasilData > 0){
        echo '<script>alert("Maaf! kode produk sudah ada");history.go(-1);</script>';
    } else{
        $cekDataUpdate =  mysqli_query($conn, "UPDATE produk SET kode_produk='$kodeproduk1',
        nama_produk='$namaproduk1',harga_modal='$harga_modal1',harga_jual='$harga_jual1'
         WHERE idproduk='$idproduk1'") or die(mysqli_connect_error());
        if($cekDataUpdate){
            echo '<script>history.go(-1);</script>';
        } else {
            echo '<script>alert("Gagal Edit Data Produk");history.go(-1);</script>';
        }
    }
}; 
	if(!empty($_GET['hapus'])){
		$idproduk1 = $_GET['hapus'];
		$hapus_data = mysqli_query($conn, "DELETE FROM produk WHERE idproduk='$idproduk1'");
        if($hapus_data){
            echo '<script>history.go(-1);</script>';
        } else {
            echo '<script>alert("Gagal Hapus Data Produk");history.go(-1);</script>';
        }
	};
    ?>
<!-- Modal Tambah Produk -->
<div class="modal fade" id="TambahProduk" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content border-0">
        <form method="post">
      <div class="modal-header bg-purple">
        <h5 class="modal-title text-white">Tambah Produk</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <label class="samll">Kode Produk :</label>
            <input type="text" name="Tambah_Kode_Produk" class="form-control" required>
        </div>
        <div class="form-group">
            <label class="samll">Nama Produk :</label>
            <input type="text" name="Tambah_Nama_Produk" class="form-control" required>
        </div>
        <div class="form-group">
            <label class="samll">Harga Modal :</label>
            <input type="number" placeholder="0" name="Tambah_Harga_Modal" class="form-control" required>
        </div>
        <div class="form-group">
            <label class="samll">Harga Jual :</label>
            <input type="number" placeholder="0" name="Tambah_Harga_Jual" class="form-control" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="submit" name="TambahProduk" class="btn btn-primary">Simpan</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- end Modal Produk -->

<!-- end isinya -->
<?php include 'footer.php'; ?>