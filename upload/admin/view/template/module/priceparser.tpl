<?php echo $header; ?>

<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
      <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>

  <div class="box">
    <div class="left"></div>
    <div class="right"></div>
    
    <div class="heading">
      <h1 style="background-image: url('view/image/feed.png') no-repeat;"><?php echo $lang['heading_title']; ?></h1>
      <div class="buttons">
        <a onclick="$('#form').submit();" class="button"><span><?php echo $lang['button_save']; ?></span></a>
        <a href="<?php echo $cancel; ?>" class="button"><span><?php echo $lang['button_cancel']; ?></span></a>
      </div>
    </div>
    
    <div class="content">
      <div id="tabs" class="htabs">
        <a href="#tab-import"><?php echo $lang['tab_import']; ?></a>
        <a href="#tab-settings"><?php echo $lang['tab_settings']; ?></a>
      </div>
      <div id="tab-import">
        <form action="<?php echo $import; ?>" method="post" enctype="multipart/form-data" id="form-import">
          <select name="format">
            <?php foreach ($parsers as $parser) { ?>
              <option value="<?php echo $parser; ?>">*.<?php echo $parser; ?></option>
            <?php } ?>
          </select>
          <select name="vendor">
            <?php foreach ($vendors as $vendor) { ?>
              <option value="<?php echo $vendor; ?>"><?php echo $vendor; ?></option>
            <?php } ?>
          </select>
          <input type="file" name="file">
          <button type="submit"><?php echo $lang['button_run']; ?></button>
        </form>
      </div>
      <div id="tab-settings">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-settings">
          <table class="form">
            <tr>
              <td></td>
              <td></td>
            </tr>
          </table>
        </form>
      </div>
    </div>

    <div style="text-align: center; line-height: 1.4; padding-top: 20px;">

    </div>
  </div>
</div>

<script type="text/javascript" src="//yandex.st/jquery/form/3.14/jquery.form.min.js"></script>

<script type="text/javascript"><!--
$('#tabs a').tabs();
//--></script>

<script type="text/javascript"><!--
$('#form-import').ajaxForm({
  beforeSubmit: function() {
    $('#form-import button')
      .attr('disabled', true)
      .text("<?php echo $lang['button_processing']; ?> ...")
    ;
  },
  success: function(res) {
    alert(
      "<?php echo $lang['import_completed']; ?> \n\n" +
      "<?php echo $lang['products_created']; ?>: " + res.created + "\n" +
      "<?php echo $lang['products_updated']; ?> " + res.updated
    );

    $('#form-import button')
      .attr('disabled', false)
      .text("<?php echo $lang['button_run']; ?>")
    ;
  }
});
//--></script>

<?php echo $footer; ?>
