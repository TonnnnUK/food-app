class CalendarService {

    months = {
        0: 'January',
        1: 'February',
        2: 'March',
        3: 'April',
        4: 'May',
        5: 'June',
        6: 'July',
        7: 'August',
        8: 'September',
        9: 'October',
        10: 'November',
        11: 'December',
    };

    days = {
        1: 'mon', 
        2: 'tue', 
        3: 'wed', 
        4: 'thurs', 
        5: 'fri', 
        6: 'sat',
        0: 'sun'
    }; 

    getCalendarDates(currentDate) {

        // Get the year and month of the current date
        const currentYear = currentDate.getFullYear();
        const currentMonth = currentDate.getMonth();

        // Get the last day of the previous month
        const lastDayOfPrevMonth = new Date(currentYear, currentMonth, 0).getDate();

        // Calculate the starting and ending dates for the last week of the previous month
        const lastWeekOfPrevMonthStart = new Date(currentYear, currentMonth - 1, lastDayOfPrevMonth - 6);
        const lastWeekOfPrevMonthEnd = new Date(currentYear, currentMonth, 0);

        // Calculate the starting and ending dates for the current month
        const currentMonthStart = new Date(currentYear, currentMonth, 1);
        const currentMonthEnd = new Date(currentYear, currentMonth + 1, 0);

        // Calculate the starting and ending dates for the first week of the following month
        const firstWeekOfNextMonthStart = new Date(currentYear, currentMonth + 1, 1);
        const firstWeekOfNextMonthEnd = new Date(currentYear, currentMonth + 1, 7);

        // Generate an array of dates for the last week of the previous month
        const lastWeekOfPrevMonthDates = [];
        for (let date = lastWeekOfPrevMonthStart; date <= lastWeekOfPrevMonthEnd; date.setDate(date.getDate() + 1)) {
        lastWeekOfPrevMonthDates.push(new Date(date));
        }

        // Generate an array of dates for the current month
        const currentMonthDates = [];
        for (let date = currentMonthStart; date <= currentMonthEnd; date.setDate(date.getDate() + 1)) {
        currentMonthDates.push(new Date(date));
        }

        // Generate an array of dates for the first week of the following month
        const firstWeekOfNextMonthDates = [];
        for (let date = firstWeekOfNextMonthStart; date <= firstWeekOfNextMonthEnd; date.setDate(date.getDate() + 1)) {
        firstWeekOfNextMonthDates.push(new Date(date));
        }


        return {
            lastWeekOfPrevMonthDates,
            currentMonthDates,
            firstWeekOfNextMonthDates,
        };

    }

    generateCalendar(month){
        
        let setDate = new Date;
        setDate.setMonth(month);
        let dates = this.getCalendarDates(setDate);

        let lastWeekOfPrevMonthDays = [];
        let hitMonday = false;

        dates.lastWeekOfPrevMonthDates.forEach( (date) => {
            const options = { weekday: 'short' };
            const formattedDate = date.toLocaleDateString(undefined, options);
            if(formattedDate == 'Mon' || hitMonday ){
                hitMonday = true;
                lastWeekOfPrevMonthDays.push(date);
            }
        });

        let fullCalendar = lastWeekOfPrevMonthDays.concat(dates.currentMonthDates);
        let nextMonthsDays = dates.firstWeekOfNextMonthDates.slice(0, 35 - (fullCalendar.length) );

        fullCalendar = fullCalendar.concat(nextMonthsDays);

        return fullCalendar;
    }

    getFullDay(day){
        return (day + 1).toLocaleString('en-US', { minimumIntegerDigits: 2 })
    }

    getFullMonth(month){
        return (month + 1).toLocaleString('en-US', { minimumIntegerDigits: 2 })
    }

    twoDigitMonth(day){
        return (day.getMonth() + 1).toLocaleString('en-US', { minimumIntegerDigits: 2 })
    }
    twoDigitDay(day){
        return (day.getDate()).toLocaleString('en-US', { minimumIntegerDigits: 2 })
    }

    datesAreEqual(date1, date2){
        if( parseInt(`${date1.getFullYear()}${this.getFullMonth(date1.getMonth())}${this.getFullDay(date1.getDate())}`) == 
            parseInt(`${date2.getFullYear()}${this.getFullMonth(date2.getMonth())}${this.getFullDay(date2.getDate())}`) 
        ) {
            return true;
        } else {
            return false;
        }
    }

    dateIsBefore(date1, date2){
        if( parseInt(`${date1.getFullYear()}${this.getFullMonth(date1.getMonth())}${this.getFullDay(date1.getDate())}`) < 
            parseInt(`${date2.getFullYear()}${this.getFullMonth(date2.getMonth())}${this.getFullDay(date2.getDate())}`)
        ){
            return true;
        } else {
            return false;
        }
    }

    dateIsBeforeOrEqual( date1, date2){
        if(parseInt(`${date1.getFullYear()}${this.getFullMonth(date1.getMonth())}${this.getFullDay(date1.getDate())}`) >= 
            parseInt(`${date2.getFullYear()}${this.getFullMonth(date2.getMonth())}${this.getFullDay(date2.getDate())}`) )
        {
            return true;            
        } else {
            return false;
        }
    }


}

export { CalendarService }; 