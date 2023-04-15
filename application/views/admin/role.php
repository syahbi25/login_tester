<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <hr>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModal">
        Add New Role
    </button>
    <hr>
    <div class="row">
        <div class="col-lg-12">
            <?= form_error('menu', '<div class="alert alert-success" role="alert">', '</div>'); ?>
            <?= $this->session->flashdata('massage'); ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Role</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($role as $view) : ?>
                        <tr>
                            <th scope="row"><?= $i++; ?></th>
                            <td><?= $view['role']; ?></td>
                            <td>
                                <a href="<?= base_url('admin/roleAccess/') . $view['id_role']; ?>">
                                    <span class="badge badge-pill badge-warning">access</span></a>
                                <a href="">
                                    <span class="badge badge-pill badge-success">edit</span></a>
                                <a href="">
                                    <span class="badge badge-pill badge-danger">delete</span></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Menu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('admin/role'); ?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" name="menu" class="form-control" id="menu" placeholder="New Menu">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>