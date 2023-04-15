<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <hr>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModal">
        Add New Sub Menu
    </button>
    <hr>
    <div class="row">
        <div class="col-lg-12">
            <?= form_error('menu', '<div class="alert alert-success" role="alert">', '</div>'); ?>
            <?php if (validation_errors()) : ?>
                <div class="alert alert-danger" role="alert">
                    <?= validation_errors(); ?>
                </div>
            <?php endif; ?>
            <?= $this->session->flashdata('massage'); ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Title</th>
                        <th scope="col">Menu</th>
                        <th scope="col">URL</th>
                        <th scope="col">Icon</th>
                        <th scope="col">Active</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($submenu as $view) : ?>
                        <tr>
                            <th scope="row"><?= $i++; ?></th>
                            <td><?= $view['title']; ?></td>
                            <td><?= $view['menu']; ?></td>
                            <td><?= $view['url']; ?></td>
                            <td><?= $view['icon']; ?></td>
                            <td><?= $view['is_active']; ?></td>
                            <td>
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
            <form action="<?= base_url('menu/submenu'); ?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" name="title" class="form-control" id="title" placeholder="Submenu title">
                    </div>
                    <div class="form-group">
                        <select name="menu_id" id="menu_id" class="form-control">
                            <option value="">Select Menu</option>
                            <?php foreach ($menu as $view) : ?>
                                <option value="<?= $view['id_menu']; ?>"><?= $view['menu']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="text" name="url" class="form-control" id="url" placeholder="Submenu url">
                    </div>
                    <div class="form-group">
                        <input type="text" name="icon" class="form-control" id="icon" placeholder="Submenu icon">

                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" value="1" name="is_active" id="is_active" class="form-check-input" checked>
                            <label for="is_active" class="form-check-label">Active?</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="https://fontawesome.com/icons" target="_blank">Click here, to see the icon!</a>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>