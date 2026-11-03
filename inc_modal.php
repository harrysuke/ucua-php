<!--Modal UC-->
<form id="register_form" action="UCcode_user.php" method="post" enctype="multipart/form-data">
  <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Unsafe Condition Form</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <table width="95%" cellpadding="5" cellspacing="5" class="uitable shadow mb-4">
            <tr>
              <td align="center" valign="center"><img src="img/JPB-Logo.png" /></td>
              <td align="center" valign="center">
                <h3 class="form">FORM</h3>
              </td>
            </tr>
            <tr>
              <td><b>Department Name : PSSD (HSE)</b></td>
              <td><b>Document No.: FM-PSSD-115</b></td>
            </tr>
            <tr>
              <td colspan="2" align="center"><b>U-SEE U-ACT (UCUA)</b></td>
            </tr>
            <tr>
              <td colspan="2" align="center"></td>
            </tr>
            <tr bgcolor="#EFEFEF">
              <td colspan="2" align="left"><b>1. U-SEE</b></td>
            </tr>
            <tr align="left">
              <td colspan="2">
                <b>UNSAFE ACT (PERBUATAN TIDAK SELAMAT)</b><br />
                <div class="mb-3 row">
                  <label for="location" class="col-sm-4 col-form-label">Location
                    :</label>
                  <div class="col-sm-8">
                    <div class="input-group input-group-sm">
                      <select id="location" name="location" class="form-control js-select" required>
                        <option selected="selected"></option>
                        <?php echo funLocation(); ?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="mb-3 row">
                  <label for="offenceCode" class="col-sm-4 col-form-label">Condition Details :</label>
                  <div class="col-sm-8">
                    <div class="input-group input-group-sm">
                      <textarea class="form-control" id="details" name="details" aria-label="Sizing example input"
                        aria-describedby="inputGroup-sizing-sm" required></textarea>
                    </div>
                  </div>
                </div>
                <div class="mb-3 row">
                  <label for="offenceCode" class="col-sm-4 col-form-label">Evidence :</label>
                  <div class="col-sm-8">
                    <input class="form-control" type="file" name="files[]" id="files"
                      accept="image/png, image/gif, image/jpeg" multiple="multiple" required>
                  </div>
                </div>
                <div class="mb-3 row">
                  <label for="offenceCode" class="col-sm-4 "></label>
                  <div class="col-sm-8" id="preview">
                  </div>
                </div>
              </td>
            </tr>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" name="submit_btn" class="btn btn-primary btn-icon-split btn-sm">
            <span class="text text-white">
              <i class="fa-solid fa-circle-check"></i> Submit
            </span>
          </button>
        </div>
      </div>
    </div>
  </div>
</form>
<!--End Modal-->
<!--Modal UA-->
<div class="modal fade" id="staticBackdropUA" data-bs-backdrop="static" data-keyboard="false" tabindex="-1"
  aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Unsafe Action Form</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="card-footer">
          <h6>1. U-SEE</h6>
        </div>
        <b>UNSAFE ACT</b>
        <div class="form-group">
          <label for="location" class="col-form-label">Location :</label>
          <input type="text" class="form-control" id="location">
        </div>
        <div class="form-group">
          <label for="conditionDetails" class="col-form-label">Condition Details :</label>
          <textarea class="form-control" id="conditionDetails"></textarea>
        </div>
        <div class="form-group">
          <label for="location" class="col-form-label">Evidence :</label><br />
          <img src="https://via.placeholder.com/100/FFFFFF?text=1" alt="" class="img-thumbnail evidence">
          <img src="https://via.placeholder.com/100/FFFFFF?text=2" alt="" class="img-thumbnail evidence">
          <img src="https://via.placeholder.com/100/FFFFFF?text=3" alt="" class="img-thumbnail evidence">
        </div>
        <div class="card-footer">
          <h6>2. U-ACT</h6>
        </div>
        <b>IMMEDIATE CORRECTIVE ACTION TAKEN</b>
        <div class="row">
          <div class="col-4">
            <div class="form-check">
              <input class="form-check-input" type="radio" name="radio1" value="STOP WORK">
              <label class="form-check-label">STOP WORK</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="radio1" value="VERBAL WARNING">
              <label class="form-check-label">VERBAL WARNING</label>
            </div>

          </div>
          <div class="col-4">
            <div class="form-check">
              <input class="form-check-input" type="radio" name="radio1" value="REVOKE ENTRY PERMIT">
              <label class="form-check-label">REVOKE ENTRY PERMIT</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="radio1" value="NOTIFY RELATED DEPARTMENT">
              <label class="form-check-label">NOTIFY RELATED DEPARTMENT</label>
            </div>
          </div>
          <div class="col-4">
            <div class="form-check">
              <input class="form-check-input" type="radio" name="radio1" value="OTHERS">
              <label class="form-check-label">OTHERS</label>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" id="btnUA" class="btn btn-primary">Submit</button>
      </div>
    </div>
  </div>
</div>
<!--End Modal-->