<template>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">Введите параметры для расчета</div>
                    <div class="panel-body">
                        <form>
                            <div class="form-group">
                                <label for="sum">Сумма кредита</label>
                                <input v-model="form.sum" type="number" id="sum" class="form-control" placeholder="Сумма кредита..."/>
                            </div>
                            <div class="form-group">
                                <label for="range">Срок кредита(мес.)</label>
                                <input v-model="form.range" type="number" id="range" class="form-control" placeholder="Срок кредита..."/>
                            </div>
                            <div class="form-group">
                                <label for="rate">Процентная ставка(% в год)</label>
                                <input v-model="form.rate" type="number" id="rate" class="form-control" placeholder="Процентная ставка..."/>
                            </div>
                            <div class="form-group">
                                <label for="start">Начало выплат</label>
                                <div id="start" class="input-group">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                            <span v-if="form.month">{{ monthList[form.month - 1] }}</span>
                                            <span v-else>Выберите месяц</span>
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li v-for="(month, index) in monthList">
                                                <a class="dropdown-item" href="#" @click.prevent=selectMonth(index)>{{ month }}</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <input v-model="form.year" type="text" class="form-control" placeholder="Введите год..."/>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="button" class="btn btn-success pull-right" @click.prevent="calculate">Рассчитать</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">График платежей</div>
                    <div class="panel-body">
                        <table class="table" v-if="data.length">
                            <thead>
                                <th>#</th>
                                <th>Дата платежа</th>
                                <th>Основной долг</th>
                                <th>Сумма платежа</th>
                                <th>Проценты</th>
                                <th>Остаток долга</th>
                            </thead>
                            <tbody>
                                <tr v-for="row in data">
                                    <td>{{ row.position }}</td>
                                    <td>{{ monthList[row.month - 1] }}, {{ row.year }}</td>
                                    <td>{{ row.main_debt }}</td>
                                    <td>{{ row.payment }}</td>
                                    <td>{{ row.percent_payment }}</td>
                                    <td>{{ row.debt }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div v-else>Нет данных для отображения...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="babel">
    import axios from 'axios';

    export default {
        data() {
            return {
                data: [],
                monthList: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
                form: {
                    month: 1
                },
            }
        },
        methods: {
            selectMonth(index) {
                this.form.month = index + 1;
            },
            calculate() {
                axios.post('/api/credit/calculate', this.form).then(response => {
                    this.data = response.data
                }).catch(e => {
                    console.log(e);
                })
            }
        }
    }
</script>
