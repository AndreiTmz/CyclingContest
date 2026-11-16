<?php
    $form = $search_form ?? [];
    $escape_search_value = static fn($value) => htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    $style_attribute = !empty($form['style'])
        ? ' style="' . $escape_search_value($form['style']) . '"'
        : '';
?>
<form class="search-form" action="<?php echo $escape_search_value($form['action']); ?>" method="post"<?php echo $style_attribute; ?>>
    <b><?php echo $escape_search_value($form['label']); ?>:</b>
    <input type="text" name="<?php echo $escape_search_value($form['name_field']); ?>"
        placeholder="<?php echo $escape_search_value($form['placeholder']); ?>"
        value="<?php echo $escape_search_value($form['name_value']); ?>" />
    <?php if (!empty($form['csrf_token'])) { ?>
        <input type="hidden" name="csrf_token" value="<?php echo $escape_search_value($form['csrf_token']); ?>" />
    <?php } ?>

    <div class="search-filters">
        <div>
            <input type="radio" name="gender" value="" <?php echo $form['gender_value'] === '' ? 'checked' : ''; ?> /> All
            <input type="radio" name="gender" value="M" <?php echo $form['gender_value'] === 'M' ? 'checked' : ''; ?> /> Male
            <input type="radio" name="gender" value="F" <?php echo $form['gender_value'] === 'F' ? 'checked' : ''; ?> /> Female
        </div>

        <div>
            <input type="radio" name="age_category" value="" <?php echo $form['age_category_value'] === '' ? 'checked' : ''; ?> /> All
            <input type="radio" name="age_category" value="JUNIOR" <?php echo $form['age_category_value'] === 'JUNIOR' ? 'checked' : ''; ?> /> <?php echo age_category::JUNIOR->value; ?>
            <input type="radio" name="age_category" value="INTERMEDIATE" <?php echo $form['age_category_value'] === 'INTERMEDIATE' ? 'checked' : ''; ?> /> <?php echo age_category::INTERMEDIATE->value; ?>
            <input type="radio" name="age_category" value="SENIOR" <?php echo $form['age_category_value'] === 'SENIOR' ? 'checked' : ''; ?> /> <?php echo age_category::SENIOR->value; ?>
        </div>
    </div>

    <input type="submit" value="Search" />
</form>