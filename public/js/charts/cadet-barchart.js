$(function() {
    var chart_data = getData();
    $("#chtAnimatedBarChart").animatedBarChart({ data: chart_data });
});

getData = function() {
    return [
        {"group_name": "Quirat", "name": "Jan", "value": 38367},
        {"group_name": "Quirat", "name": "Feb", "value": 32684},
        {"group_name": "Quirat", "name": "Mar", "value": 28236},
        {"group_name": "Quirat", "name": "Apr", "value": 44205},
        {"group_name": "Quirat", "name": "May", "value": 3357},
        {"group_name": "Quirat", "name": "Jun", "value": 3511},
        {"group_name": "Quirat", "name": "Jul", "value": 10372},
        {"group_name": "Quirat", "name": "Aug", "value": 15565},
        {"group_name": "Quirat", "name": "Sep", "value": 23752},
        {"group_name": "Quirat", "name": "Oct", "value": 28927},
        {"group_name": "Quirat", "name": "Nov", "value": 21795},
        {"group_name": "Quirat", "name": "Dec", "value": 49217},
        {"group_name": "Azan", "name": "Jan", "value": 28827},
        {"group_name": "Azan", "name": "Feb", "value": 13671},
        {"group_name": "Azan", "name": "Mar", "value": 27670},
        {"group_name": "Azan", "name": "Apr", "value": 6274},
        {"group_name": "Azan", "name": "May", "value": 12563},
        {"group_name": "Azan", "name": "Jun", "value": 31263},
        {"group_name": "Azan", "name": "Jul", "value": 24848},
        {"group_name": "Azan", "name": "Aug", "value": 41199},
        {"group_name": "Azan", "name": "Sep", "value": 18952},
        {"group_name": "Azan", "name": "Oct", "value": 30701},
        {"group_name": "Azan", "name": "Nov", "value": 16554},
        {"group_name": "Azan", "name": "Dec", "value": 36399},
        {"group_name": "Painting", "name": "Jan", "value": 38674},
        {"group_name": "Painting", "name": "Feb", "value": 9595},
        {"group_name": "Painting", "name": "Mar", "value": 7520},
        {"group_name": "Painting", "name": "Apr", "value": 2568},
        {"group_name": "Painting", "name": "May", "value": 6583},
        {"group_name": "Painting", "name": "Jun", "value": 44485},
        {"group_name": "Painting", "name": "Jul", "value": 3405},
        {"group_name": "Painting", "name": "Aug", "value": 31709},
        {"group_name": "Painting", "name": "Sep", "value": 45442},
        {"group_name": "Painting", "name": "Oct", "value": 37580},
        {"group_name": "Painting", "name": "Nov", "value": 23445},
        {"group_name": "Painting", "name": "Dec", "value": 7554},
        {"group_name": "Acting", "name": "Jan", "value": 40110},
        {"group_name": "Acting", "name": "Feb", "value": 35605},
        {"group_name": "Acting", "name": "Mar", "value": 15768},
        {"group_name": "Acting", "name": "Apr", "value": 15075},
        {"group_name": "Acting", "name": "May", "value": 12424},
        {"group_name": "Acting", "name": "Jun", "value": 12227},
        {"group_name": "Acting", "name": "Jul", "value": 40906},
        {"group_name": "Acting", "name": "Aug", "value": 34032},
        {"group_name": "Acting", "name": "Sep", "value": 18110},
        {"group_name": "Acting", "name": "Oct", "value": 4755},
        {"group_name": "Acting", "name": "Nov", "value": 42202},
        {"group_name": "Acting", "name": "Dec", "value": 36183}
    ];
}