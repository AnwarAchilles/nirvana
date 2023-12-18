NV.component(
  class Toolkit {

    constructor({}) {}

    uniqid() {
      return Math.random().toString(36).substring(2) + Date.now().toString(36);
    }

    formatNumber( number ) {
      const result = parseFloat(number).toLocaleString('en-US',{minimumIntegerDigits: 2});
      if(result=="00") {
        return "0";
      }else {
        return result;
      }
    }

    dateTimeNow() {
      const now = new Date();
    
      const year = now.getFullYear();
      const month = String(now.getMonth() + 1).padStart(2, '0');
      const day = String(now.getDate()).padStart(2, '0');
      const hour = String(now.getHours()).padStart(2, '0');
      const minute = String(now.getMinutes()).padStart(2, '0');
      const second = String(now.getSeconds()).padStart(2, '0');
    
      return `${year}-${month}-${day} ${hour}:${minute}:${second}`;
    }

    dateTimeNowNoSecond() {
      const now = new Date();
    
      const year = now.getFullYear();
      const month = String(now.getMonth() + 1).padStart(2, '0');
      const day = String(now.getDate()).padStart(2, '0');
      const hour = String(now.getHours()).padStart(2, '0');
      const minute = String(now.getMinutes()).padStart(2, '0');
    
      return `${year}-${month}-${day} ${hour}:${minute}`;
    }


    datetimeAgo( datetime ) {
      // Get the created_at timestamp from your data
      const created_at = datetime;

      // Convert the timestamp to a JavaScript Date object
      const createdAtDate = new Date(created_at);

      // Get the current date and time
      const currentDate = new Date();

      // Calculate the time difference in milliseconds
      const timeDifference = currentDate - createdAtDate;

      // Convert the time difference to minutes, hours, or days
      const minutesAgo = Math.floor(timeDifference / (1000 * 60));
      const hoursAgo = Math.floor(timeDifference / (1000 * 60 * 60));
      const daysAgo = Math.floor(timeDifference / (1000 * 60 * 60 * 24));
      const monthsAgo = Math.floor(timeDifference / (1000 * 60 * 60 * 24 * 30));
      const yearsAgo = Math.floor(timeDifference / (1000 * 60 * 60 * 24 * 365));

      // Create a "time" representation based on the time difference
      let timeAgo;
      
      if (yearsAgo > 0) {
        timeAgo = `${yearsAgo} ${yearsAgo === 1 ? "year" : "years"}`;
      } else if (monthsAgo > 0) {
          timeAgo = `${monthsAgo} ${monthsAgo === 1 ? "month" : "months"}`;
      } else if (daysAgo > 0) {
          timeAgo = `${daysAgo} ${daysAgo === 1 ? "day" : "days"}`;
      } else if (hoursAgo > 0) {
          timeAgo = `${hoursAgo} ${hoursAgo === 1 ? "hour" : "hours"}`;
      } else {
          timeAgo = `${minutesAgo} ${minutesAgo === 1 ? "minute" : "minutes"}`;
      }

      return timeAgo;
    }

    removeHTML(input) {
      return input.replace(/<\/?[^>]+(>|$)/g, "");
    }

    textCutWord(text, maxWords) {
      const words = text.split(' ');
      if (words.length <= maxWords) {
        return text; // If the text has fewer words than maxWords, return the original text.
      }
      const truncatedText = words.slice(0, maxWords).join(' ');
      return truncatedText;
    }

    percentage(value, total) {
      return Math.round((parseInt(value) / parseInt(total)) * 100);  
    }

    clearnURL(url) {
      return url.replace(/\/\//g, '/');
    }

    convertDate( datetime ) {
      const dateObject = new Date(datetime);
      const resultArray = {
        'year': dateObject.getFullYear(),
        'month': dateObject.getMonth() + 1, // Bulan dimulai dari 0, sehingga tambahkan 1
        'monthname': dateObject.toLocaleDateString('en-US', { month: 'long' }),
        'day': dateObject.getDate(),
        'dayname': dateObject.toLocaleDateString('en-US', { weekday: 'long' }),
        'hour': dateObject.getHours(),
        'minute': dateObject.getMinutes(),
        'second': dateObject.getSeconds()
      };
      return resultArray;
    }

    formatDate(datetime) {
      let date = this.convertDate(datetime);
      return `${date.day} ${date.monthname} ${date.year} - ${date.hour}:${date.minute}`;
    }

    formatToTwoDigits(number) {
      return number < 10 ? '0' + number : number.toString();
    }


  }
);