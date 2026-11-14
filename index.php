<?php
    $page_title="Cycling contest 2017";
    $section="Home";
    include("shared/header.php");
    include("shared/enums.php");
?>

<div id="content">
<h2>Welcome to the 2017 Cycling Competition!</h2>
    <div id="info">
        <em class="indented">Cycling</em> is an activity enjoyed by most people.
        Whether we ride to school, to work, or simply for pleasure, cycling has many benefits.
        Therefore, we invite you to participate in the 2017 edition of the cycling competition.
        The MTB and Downhill competitions take place on forest trails in the surroundings
        of the town of Azuga, while the BMX and Trial competitions are held at specially
        designated locations in the town center.

        <h3>Competition Rules:</h3>

        <ul>
            <li>
                Participants must bring their own protective equipment and their own
                bicycle, appropriate for each competition category.
            </li>
            <li>
                Wearing protective equipment is mandatory:
                <ul>
                    <li>Helmet (MTB)</li>
                    <li>Helmet + knee pads + elbow pads (Downhill, BMX, Trial)</li>
                </ul>
            </li>
            <li>
                Registration must be confirmed at the competition venue by presenting
                an identity document containing information matching that provided
                in the online registration form.
            </li>
            <li>
                The competition organizers and their relatives are not eligible to participate.
            </li>
            <li>
                Blocking the trails and deliberately obstructing other competitors
                through various means to slow them down are strictly prohibited.
                Any participant who engages in such behavior will be disqualified.
            </li>
        </ul>

        <h3>Recommendations</h3>
        <ul>
            <li>
                Participants in MTB and DOWNHILL are especially advised to carry the
                tools needed to repair punctures in their backpacks, as the routes
                will follow rough, uneven roads.
            </li>
            <li>
                Participants are also advised to bring water and food, as the routes
                are long and physically demanding.
            </li>
        </ul>

        <h3>Prizes</h3>

        1st Place: Gold medal + bicycle <br />
        2nd Place: Silver medal + rollerblades <br />
        3rd Place: Bronze medal + 5 free bicycle repairs at our repair shop
    </div>

</div>

<div id="left">
    <h2>Competition Categories</h2>

    <ul>
        <li>
            <h4>Mountain Bike (MTB)</h4>
            <ul>
                <li><?php echo age_category::JUNIOR->value; ?></li>
                <li><?php echo age_category::INTERMEDIATE->value; ?></li>
                <li><?php echo age_category::SENIOR->value; ?></li>
            </ul>
        </li>

        <li>
            <h4>Downhill</h4>
            <ul>
                <li><?php echo age_category::JUNIOR->value; ?></li>
                <li><?php echo age_category::INTERMEDIATE->value; ?></li>
                <li><?php echo age_category::SENIOR->value; ?></li>
            </ul>
        </li>

        <li>
            <h4>BMX</h4>
            <ul>
                <li><?php echo age_category::JUNIOR->value; ?></li>
                <li><?php echo age_category::INTERMEDIATE->value; ?></li>
                <li><?php echo age_category::SENIOR->value; ?></li>
            </ul>
        </li>

        <li>
            <h4>Trial</h4>
            <ul>
                <li><?php echo age_category::JUNIOR->value; ?></li>
                <li><?php echo age_category::INTERMEDIATE->value; ?></li>
                <li><?php echo age_category::SENIOR->value; ?></li>
            </ul>
        </li>
    </ul>
</div>

<?php include("shared/footer.php");?>