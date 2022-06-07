<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basket Ball Score Sheet</title>
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        .d-flex{
            display: flex;
        }
        .justify-content-between{
            justify-content: space-between;
        }
        .item-center{
            align-items: center;
            text-align: center;
        }


        th, td {
            padding:
                    5px;
        }

        input[type=checkbox]{
            position: relative;
            vertical-align: bottom;
            bottom: 1px;
        }

    </style>
</head>
<body>
<div class="container">
    <table style="width: 100%;">
        <tbody>
        <tr>
            <td  colspan="26">
                Competition :......... Date....... Time ...... Referee.... <br>
                Game No ...... Place.... Umpire..
            </td>
        </tr>
        <tr>
            <td rowspan="" colspan="6" style="border-right: none;" >
                <h4> Team A:</h4>
                <h5> Time Outs</h5>
                (1)<input type="checkbox" name="" id="">(2)  <input type="checkbox" name="" id=""> <br>
                (3)  <input type="checkbox" name="" id=""> (4) <input type="checkbox" name="" id=""> <input type="checkbox" name="" id="">
                <br><input type="checkbox" name="" id=""><input type="checkbox" name="" id=""><input type="checkbox" name="" id=""> Extra Periods

            </td>
            <td colspan="7" style="border-left: none;">

                <h5>Team Fouls </h5>
                Period(1) <input type="checkbox" name="" id="" style="padding: 0;margin-top: 5px;"><input type="checkbox" name="" id=""><input type="checkbox" name="" id=""><input type="checkbox" name="" id="">
                (2) <input type="checkbox" name="" id=""><input type="checkbox" name="" id=""><input type="checkbox" name="" id=""><input type="checkbox" name="" id="">
                <br>
                Period(3) <input type="checkbox" name="" id=""><input type="checkbox" name="" id=""><input type="checkbox" name="" id=""><input type="checkbox" name="" id="">
                (4) <input type="checkbox" name="" id=""><input type="checkbox" name="" id=""><input type="checkbox" name="" id=""><input type="checkbox" name="" id="">
                <br>

            </td>


            <td rowspan="" colspan="6" style="border-right: none;" >
                <h4> Team B:</h4>


                <h5 > Time Outs</h5>
                <label for="">(1)</label><input type="checkbox" name="" id=""> <label for="">(2)</label><input type="checkbox" name="" id=""> <br>
                <label for="">(3)</label><input type="checkbox" name="" id=""> <label for="">(4)</label>  <input type="checkbox" name="" id=""> <input type="checkbox" name="" id="">
                <br><input type="checkbox" name="" id=""><input type="checkbox" name="" id=""><input type="checkbox" name="" id=""> Extra Periods

            </td>
            <td colspan="7" style="border-left: none;">

                <h5>Team Fouls </h5>
                Period(1) <input type="checkbox" name="" id="" style="padding: 0;margin-top: 5px;"><input type="checkbox" name="" id=""><input type="checkbox" name="" id=""><input type="checkbox" name="" id="">
                (2) <input type="checkbox" name="" id=""><input type="checkbox" name="" id=""><input type="checkbox" name="" id=""><input type="checkbox" name="" id="">
                <br>
                Period(3) <input type="checkbox" name="" id=""><input type="checkbox" name="" id=""><input type="checkbox" name="" id=""><input type="checkbox" name="" id="">
                (4) <input type="checkbox" name="" id=""><input type="checkbox" name="" id=""><input type="checkbox" name="" id=""><input type="checkbox" name="" id="">
                <br>

            </td>












        </tr>
        <tr>
            <td colspan="1" rowspan="2">
                S/ <br>
                N
            </td>
            <td colspan="5" rowspan="2" class="item-center">
                <h6>Players</h6>
            </td>
            <td colspan="1" rowspan="2">
                J/ <br>
                N
            </td>
            <td colspan="1" rowspan="2">
                S

            </td>
            <td colspan="5" rowspan="1">
                <h6>Fouls</h6>
            </td>
            <td colspan="1" rowspan="2">
                S/ <br>
                N
            </td>
            <td colspan="5" rowspan="2">
                <h6>Players</h6>
            </td>
            <td colspan="1" rowspan="2">
                J/ <br>
                N
            </td>
            <td colspan="1" rowspan="2">
                S

            </td>
            <td colspan="5" rowspan="1">
                <h6>Fouls</h6>
            </td>
        </tr>
        <tr>

            <td  colspan="1" rowspan="1" >1</td>
            <td  colspan="1" rowspan="1">2</td>
            <td  colspan="1" rowspan="1">3</td>
            <td  colspan="1" rowspan="1">4</td>
            <td   colspan="1" rowspan="1">5</td>
            <td  colspan="1" rowspan="1">1</td>
            <td  colspan="1" rowspan="1">2</td>
            <td  colspan="1" rowspan="1">3</td>
            <td  colspan="1" rowspan="1">4</td>
            <td  colspan="1" rowspan="1">5</td>
        </tr>
        <tr>
            <!-- Team A -->
            <td colspan="1">1</td>

            <td colspan="5"></td>
            <td colspan="1"></td>
            <td colspan="1"></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>

            <!--Team B -->
            <td colspan="1">1</td>

            <td colspan="5"></td>
            <td colspan="1"></td>
            <td colspan="1"></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
        </tr>

        <tr>
            <!-- Team A -->
            <td colspan="1">2</td>

            <td colspan="5"></td>
            <td colspan="1"></td>
            <td colspan="1"></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>

            <!--Team B -->
            <td colspan="1">2</td>

            <td colspan="5"></td>
            <td colspan="1"></td>
            <td colspan="1"></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
        </tr>
        <tr>
            <!-- Team A -->
            <td colspan="1">3</td>

            <td colspan="5"></td>
            <td colspan="1"></td>
            <td colspan="1"></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>

            <!--Team B -->
            <td colspan="1">3</td>

            <td colspan="5"></td>
            <td colspan="1"></td>
            <td colspan="1"></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
        </tr>
        <tr>
            <!-- Team A -->
            <td colspan="1">4</td>

            <td colspan="5"></td>
            <td colspan="1"></td>
            <td colspan="1"></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>

            <!--Team B -->
            <td colspan="1">4</td>

            <td colspan="5"></td>
            <td colspan="1"></td>
            <td colspan="1"></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
        </tr>
        <tr>
            <!-- Team A -->
            <td colspan="1">5</td>

            <td colspan="5"></td>
            <td colspan="1"></td>
            <td colspan="1"></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>

            <!--Team B -->
            <td colspan="1">5</td>

            <td colspan="5"></td>
            <td colspan="1"></td>
            <td colspan="1"></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
        </tr>
        <tr>
            <!-- Team A -->
            <td colspan="1">6</td>

            <td colspan="5"></td>
            <td colspan="1"></td>
            <td colspan="1"></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>

            <!--Team B -->
            <td colspan="1">6</td>

            <td colspan="5"></td>
            <td colspan="1"></td>
            <td colspan="1"></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
        </tr>
        <tr>
            <!-- Team A -->
            <td colspan="1">7</td>

            <td colspan="5"></td>
            <td colspan="1"></td>
            <td colspan="1"></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>

            <!--Team B -->
            <td colspan="1">7</td>

            <td colspan="5"></td>
            <td colspan="1"></td>
            <td colspan="1"></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
        </tr>
        <tr>
            <!-- Team A -->
            <td colspan="1">8</td>

            <td colspan="5"></td>
            <td colspan="1"></td>
            <td colspan="1"></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>

            <!--Team B -->
            <td colspan="1">8</td>

            <td colspan="5"></td>
            <td colspan="1"></td>
            <td colspan="1"></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
        </tr>
        <tr>
            <!-- Team A -->
            <td colspan="1">9</td>

            <td colspan="5"></td>
            <td colspan="1"></td>
            <td colspan="1"></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>

            <!--Team B -->
            <td colspan="1">9</td>

            <td colspan="5"></td>
            <td colspan="1"></td>
            <td colspan="1"></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
        </tr>
        <tr>
            <!-- Team A -->
            <td colspan="1">10</td>

            <td colspan="5"></td>
            <td colspan="1"></td>
            <td colspan="1"></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>

            <!--Team B -->
            <td colspan="1">10</td>

            <td colspan="5"></td>
            <td colspan="1"></td>
            <td colspan="1"></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
        </tr>
        <tr>
            <!-- Team A -->
            <td colspan="1">11</td>

            <td colspan="5"></td>
            <td colspan="1"></td>
            <td colspan="1"></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>

            <!--Team B -->
            <td colspan="1">11</td>

            <td colspan="5"></td>
            <td colspan="1"></td>
            <td colspan="1"></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
            <td colspan="1"> <input type="checkbox" name="" id=""></td>
        </tr>
        <tr>
            <td colspan="13">
                Coach:
            </td>
            <td colspan="13">
                Coach:
            </td>
        </tr>
        <tr>
            <td colspan="13">
                Assistant Coach:
            </td>
            <td colspan="13">
                Assistant Coach:
            </td>
        </tr>
        <tr>
            <td colspan="1" style="border-right: none;">
                <b>Scorer :</b>
            </td>
            <td colspan="12" style="border-left: none;">


                Period(1)A____ B____(2)A___B___ <br>
                Period(3)A____ B____(4)A___B___ <br>
                Extra Period : A_____ B____

            </td>
            <td colspan="13" >
                Final Score : Team A _____ Team B _____ <br>
                Name of Winning Team

            </td>

        </tr>
        <tr>
            <td colspan="13">
                ScoreKeeper: <br>
                TimeKeeper: <br>
                Team Captain 1:

            </td>
            <td colspan="13">
                Referee: <br>
                Umpire: <br>
                Champion's Signature incase of protest:

            </td>

        </tr>
        </tbody>
    </table>
</div>

</body>
</html>

