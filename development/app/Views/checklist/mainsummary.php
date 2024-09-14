<div class="container">
    <div class="col-12 col-sm-12 offset-sm-12 col-md-12 mt-1 pt-3 pb-3 bg-white from-wrapper " >
        <div class="countainer" style="  border: 1px solid; padding: 10px; box-shadow: 5px 10px 18px #888888;">
        <h5 style=" border: 1px solid; padding: 10px;">All Created checklist</h5>
            <hr style="height:2px;border-width:0;color:gray;background-color:gray">

            <table class="table">
            <thead>
                <tr>
                <th scope="col">Checklist ID</th>
                <th scope="col">Audit Category</th>
                <th scope="col">Audit Type</th>
                <th scope="col">Audit Area</th>
                <th scope="col">Checklist Description </th>
                </tr>
            </thead>
            <tbody>
                <?php if($mainchecklist):?>
                    <?php foreach($mainchecklist as $row): ?>
                <tr>
                <th scope="row"><?php echo $row['checklist_id'];?> </th>
                <td><?php echo $row['audit_category'];?> </td>
                <td><?php echo $row['audit_type'];?> </td>
                <td><?php echo $row['audit_area'];?> </td>
                <td><?php echo $row['checklist_description'];?> </td>
                <td><a href="<?= base_url('/Development/development/public/mainChecklistEdit/'.$row['id']); ?>" class="btn btn-secondary">Edit</a></td>
                <td><a href="<?= base_url('/Development/development/public/mainChecklistDelete/'.$row['checklist_id']); ?>" class="btn btn-danger " onclick="return confirm('Do you realy need to delete selected item?')" >Delete</a></td>
                </tr>
                <?php endforeach; ?>    
                <?php endif; ?>
            </tbody>
            </table>

        </div>
    </div>    
</div>

<script type="text/javascript">
    $('.confirmation').on('click', function () {
        return confirm('Are you sure?');
    });
</script>