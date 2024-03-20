<template>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col" v-for="campo, key in campos" :key="key">{{campo}}</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="obj in dados" :key="obj.id">
                <td v-for="valor, chave in obj" :key="chave">
                    <span v-if="campos.includes(chave)">
                    <span v-if="chave == 'imagem'">
                        <img :src="'/storage/'+valor" height="30" width="30">
                    </span>
                    <span v-else>
                        {{valor}}
                    </span>
                    </span>
                </td>
                <!-- <td><img :src="'/storage/'+row.imagem" height="30" width="30"></td> -->
            </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        props: [
            'campos',
            'dados'
        ]
    }
</script>
