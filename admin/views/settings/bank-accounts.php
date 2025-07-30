<div class="col-md-8">
  <div class="settings-header__table">
    <button type="button"  class="btn btn-default m-b" data-toggle="modal" data-target="#modalDiv" data-action="new_bankaccount" >Tài khoản ngân hàng mới</button>
  </div>
   <table class="table">
      <thead>
         <tr>
            <th>
              Tên ngân hàng
            </th>
            <th>
               Tên người nhận	
            </th>
            <th>
              IBAN
            </th>
            <th></th>
         </tr>
      </thead>
      <tbody class="methods-sortable">
         <?php foreach($bankList as $bank): ?>
           <tr>
            <td>
               <?php echo $bank["bank_name"]; ?>
            </td>
            <td><?php echo $bank["bank_alici"]; ?></td>
            <td><?php echo $bank["bank_iban"]; ?></td>
            <td class="p-r">
               <button type="button" class="btn btn-default btn-xs pull-right edit-payment-method" data-toggle="modal" data-target="#modalDiv" data-action="edit_bankaccount" data-id="<?php echo $bank["id"]; ?>">Edit</button>
            </td>
         </tr>
         <?php endforeach; ?>
      </tbody>
   </table>
</div>
