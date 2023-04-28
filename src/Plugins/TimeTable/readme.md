# Hours
Plugin to add opening hours functionality to your Chief Admin.
This will allow you to use the Opening hours api from spatie/opening-hours

## Install
First you should install the spatie opening-hours package
```bash
composer require spatie/opening-hours
```

1. Add the TimeTableServiceProvider to your list of providers
2. Run the timetable migrations
3. Add the `Thinktomorrow\Chief\Plugins\TimeTable\App\HasTimeTable` interface to the models you want to add the timetable to. Add the `Thinktomorrow\Chief\Plugins\TimeTable\Infrastructure\Models\HasTimeTableDefaults` trait as well.
4. Add the timetable field to allow a timetable to be selected on a page.
5. Add the timetable index to the Chief navigation. This can be done by adding the following code to your nav-project file

```php
<x-chief::nav.item
    label="Weekschema"
    url="{{ route('chief.timetables.index') }}"
    icon="<svg><use xlink:href='#icon-rectangle-group'></use></svg>"
    collapsible
/>
```


## Usage

Popular cases are:

List the schedule for the current week which takes into account any exceptions
```php
$hours = $model->getTimeTableHours('nl');
foreach($hours->forCurrentWeek() as $day => $weekDay) {

    echo $day;
    foreach($weekDay as $range) {
        echo '<p>' .$range->start().' - '. $range->end() . '</p>';
    }

    echo ($weekDay->getData() ? ' ' . $weekDay->getData() : '');
}
```

List the default schedule for the week
```php
$hours = $model->getTimeTableHours('nl');
$hours->forWeek() // Traversable as array like forCurrentWeek()
```

Checking if the office is currently open or not
```php
$hours = $office->getTimeTableHours('nl');
$hours->isOpen(); // true
$hours->isClosed(); // false
```

Checking if the office is currently open on a specific date
```php
$hours = $office->getTimeTableHours('nl');
$hours->currentOpenRange(now())->start(); // 8:00
$hours->currentOpenRange(now())->end(); // 17:00
```

Please check out the full documentation of the api: https://github.com/spatie/opening-hours
