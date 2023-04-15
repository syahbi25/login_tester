<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>
    <div class="row">
        <div class="col-lg-12">
            <?= form_error('menu', '<div class="alert alert-success" role="alert">', '</div>'); ?>

            <h5>
                Role : <?= $role['role']; ?>
            </h5>

            <?= $this->session->flashdata('massage'); ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Menu</th>
                        <th scope="col">Access</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($menu as $view) : ?>
                        <tr>
                            <th scope="row"><?= $i++; ?></th>
                            <td><?= $view['menu']; ?></td>
                            <td>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" <?= check_access($role['id_role'], $view['id_menu']); ?> data-role="<?= $role['id_role']; ?>" data-menu="<?= $view['id_menu']; ?>">
                                </div>
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
            <form action="<?= base_url('admin/roleAccess'); ?>" method="post">
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