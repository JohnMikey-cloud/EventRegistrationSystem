<div class="container mb-4">

    <form method="GET">

        <label class="form-label">
            Filter by Event
        </label>

        <select
            name="event_id"
            class="form-select"
            onchange="this.form.submit()"
        >

            <option value="0">
                All Events
            </option>

            <?php while($event = mysqli_fetch_assoc($events_result)){ ?>

                <option
                    value="<?php echo $event['id']; ?>"

                    <?php
                    if($selected_event_id == $event['id']){
                        echo "selected";
                    }
                    ?>
                >

                    <?php echo $event['title']; ?>

                </option>

            <?php } ?>

        </select>

    </form>

</div>