    <div class='container'>
        <div class='row'>
            <div class='col-lg-12'>
                <h4>Room Recordings</h4>
            </div>
        </div>
    </div>
    <div class='container' id='list'>
        <div class='row' id='titles'>
            <div class='col-lg-3' id='name'>
                Name
            </div>
            <div class='col-lg-4' id='thumbnails'>
                Thumbnails
            </div>
            <div class='col-lg-1' id='length'>
                Length
            </div>
            <div class='col-lg-2' id='users'>
                Users
            </div>
            <div class='col-lg-1' id='view'>
                View
            </div>
        </div>
        <?php
        foreach($recordings as $recording) {
            $meeting_name = $recording->metadata->meetingName;
            $start_time = date("F j, Y", substr($recording->startTime, 0 , -3));
            $image = $recording->playback->format->preview->images->image;
            $recording_url = $recording->playback->format->url;
            $length = $recording->playback->format->length;
            $participants = $recording->participants;

        ?>
            <div class='row' id='content'>
                <div class='col-lg-3' id='name'>
                    <p class='title'><?php echo $meeting_name; ?></p>
                    <p class='starttime'>Recorded on <?php echo $start_time; ?></p>
                </div>
                <div class='col-lg-4' id='thumbnails'>
                    <img src='<?php echo $image; ?>'>
                </div>
                <div class='col-lg-1' id='length'>
                    <p class='length'><?php echo $length; ?> min</p>
                </div>
                <div class='col-lg-2' id='participants'>
                    <p class='length'><?php echo $participants; ?></p>
                </div>
                <div class='col-lg-1' id='view'>
                    <a href='<?php echo $recording_url;?>'>Presentation</a>
                </div>
            </div>
        <?php
        }
        ?>
    </div>