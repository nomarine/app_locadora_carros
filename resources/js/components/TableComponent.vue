<template>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col" v-for="campo, key in campos" :key="key">{{campo.titulo}}</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="obj in dados" :key="obj.id">
                <td v-for="valor, chave in campos" :key="chave">
                    <span v-if="valor['tipo'] === 'imagem'">
                        <img :src="'/storage/'+obj[chave]" height="30" width="30">
                    </span>
                    <span v-else-if="valor['tipo'] === 'timestamp'">
                        {{ formatTimestamp(obj[chave]) }}
                    </span>
                    <span v-else>
                        {{obj[chave]}}
                    </span>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        props: [
            'campos',
            'dados'
        ],
        data() {
            return {
                
            }
        },
        computed: {

        },
        methods: {
            formatTimestamp(timestamp) {
                const date = new Date(timestamp);
                const formattedDate = date.toLocaleDateString('pt-BR', {
                    day: '2-digit',
                    month: '2-digit',
                    year: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit'
                });
                return formattedDate;
            },
            
        }
    }
</script>
