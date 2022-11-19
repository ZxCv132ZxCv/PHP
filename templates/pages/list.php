<div>
    <div class="message">
        <?php
        if (!empty($params['before'])) {

            switch ($params['before']) {
                case 'created':
                    echo 'Notatka została utworzona!';
                    break;
            }
        }
        ?>
    </div>
    <b><?php
        echo $params['resultList'] ?? ""; ?>
    </b>
    <h3>Lista notatek</h3>
</div>