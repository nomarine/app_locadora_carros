<template>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col" v-for="header, key in headers" :key="key">{{header}}</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="row in dados" :key="row.id">
            <th scope="row">{{row.id}}</th>
            <td>{{row.nome}}</td>
            <td><img :src="'/storage/'+row.imagem" height="30" width="30"></td>
            </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        props: [
            'headers',
            'dados'
        ]
    }
</script>
