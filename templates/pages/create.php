<h3>Nowa notatka</h3>
<div>
    <?php if ($params['created']) : ?>
        <div>
            <div>Tytuł: <?php echo $params['title'] ?></div>
            <div>Opis: <?php echo $params['description'] ?></div>
        </div>
    <?php else : ?>
        <form action="/?action=create" class="note-form" method="post">
            <ul>
                <li>
                    <label for="title">Tytuł <span class="required">*</span></label>
                    <input type="text" name="title" id="title" class="filed-long">
                </li>
                <li>
                    <label for="field5">Treść</label>
                    <textarea name="description" id="field5" class="field-long field-textarea"></textarea>
                </li>
                <li>
                    <input type="submit" value="Submit">
                </li>
            </ul>
        </form>
    <?php endif; ?>
</div>