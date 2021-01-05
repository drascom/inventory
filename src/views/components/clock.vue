<template>
<main color="blue-grey lighten-3" id="clock">
    <p class="date">{{ date }}</p>
    <p class="time">{{ time }}</p>
</main>
</template>

<script>
export default {
    data() {
        return {
            time: '',
            date: '',
            timerID : setInterval(this.updateTime, 1000),
            week : ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'],
        };
    },
    methods: {
        updateTime() {
            let cd = new Date();
            this.date = this.$moment(String(cd)).format('DD / MM / YYYY');
            this.time = this.zeroPadding(cd.getHours(), 2) + ':' + this.zeroPadding(cd.getMinutes(), 2) + ':' + this.zeroPadding(cd.getSeconds(), 2);
            // this.date = this.zeroPadding(cd.getFullYear(), 4) + '-' + this.zeroPadding(cd.getMonth() + 1, 2) + '-' + this.zeroPadding(cd.getDate(), 2) + ' ' + this.week[cd.getDay()];
        },
        zeroPadding(num, digit) {
            var zero = '';
            for (var i = 0; i < digit; i++) {
                zero += '0';
            }
            return (zero + num).slice(-digit);
        }
    },
    mounted() {
        this.updateTime();
    },
};
</script>

<style lang="scss" scoped>
main {
    width: 100%;
    height: 100%;
    background-position: center;
    background-size: cover;
    background-repeat: no-repeat;
 background: #0f3854;
    background: radial-gradient(ellipse at center, #0a2e38 0%, #000000 70%);
    display: flex;
    flex-direction: column;
    justify-content: space-around;
    align-items: center;

    box-shadow: 0 19px 38px rgba(0,0,0,0.30), 0 15px 12px rgba(0,0,0,0.22);
}


p {
    margin: 0;
    padding: 0;
}

#clock {
    font-family: 'Share Tech Mono', monospace;
    color: #ffffff;
    text-align: center;
    // left: 50%;
    // top: 50%;
    // transform: translate(-50%, -50%);
    color: #daf6ff;
    text-shadow: 0 0 20px rgba(10, 175, 230, 1), 0 0 20px rgba(10, 175, 230, 0.5);

    .time {
        letter-spacing: 0.05em;
        font-size: 40px;
        padding: 5px 0;
    }

    .date {
        letter-spacing: 0.1em;
        font-size: 24px;
    }

    .text {
        letter-spacing: 0.1em;
        font-size: 12px;
        padding: 20px 0 0;
    }
}
@media screen and (min-width: 450px) {
    main {
        width: 330px;
        border-radius: 5px;
    }
}
</style>
