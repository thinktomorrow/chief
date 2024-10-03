# TimeTable

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
    icon='<svg viewBox="0 0 24 24" color="currentColor" fill="none"> <path d="M18 2V4M6 2V4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /> <path d="M11.9955 13H12.0045M11.9955 17H12.0045M15.991 13H16M8 13H8.00897M8 17H8.00897" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /> <path d="M3.5 8H20.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /> <path d="M2.5 12.2432C2.5 7.88594 2.5 5.70728 3.75212 4.35364C5.00424 3 7.01949 3 11.05 3H12.95C16.9805 3 18.9958 3 20.2479 4.35364C21.5 5.70728 21.5 7.88594 21.5 12.2432V12.7568C21.5 17.1141 21.5 19.2927 20.2479 20.6464C18.9958 22 16.9805 22 12.95 22H11.05C7.01949 22 5.00424 22 3.75212 20.6464C2.5 19.2927 2.5 17.1141 2.5 12.7568V12.2432Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /> <path d="M3 8H21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /> </svg>'
    collapsible
/>
```

## Usage

Popular cases are:

List the schedule for the current week which takes into account any exceptions

```php
$hours = $model->getTimeTable('nl');
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
$hours = $model->getTimeTable('nl');
$hours->forWeek() // Traversable as array like forCurrentWeek()
```

Checking if the office is currently open or not

```php
$hours = $office->getTimeTable('nl');
$hours->isOpen(); // true
$hours->isClosed(); // false
```

Checking if the office is currently open on a specific date

```php
$hours = $office->getTimeTable('nl');
$hours->currentOpenRange(now())->start(); // 8:00
$hours->currentOpenRange(now())->end(); // 17:00
```

Please check out the full documentation of the api: https://github.com/spatie/opening-hours
