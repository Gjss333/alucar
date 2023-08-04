<template>
    <div>
        <table class="table table-hover">
            <thead>
                <tr >
                    <th scope="col" v-for="t, key in titulos" :key="key">{{ t.titulo }}</th>                  
                </tr>
            </thead>
            <tbody>
                <tr v-for="obj in dados" :key="obj.id">
                    <template v-for="(valor, chave) in obj">
                        <td  v-if="titulos.includes(chave)" :key="chave">
                            <span v-if="chave == 'imagem'">
                                <img :src="'/storage/' +valor" width="40">
                            </span>
                            <span v-else>
                                {{ valor }}
                            </span>
                        </td>
                    </template>


                    <!-- <th scope="row">{{ m.id }}</th>
                    <td>{{m.nome}}</td>
                    <td><img width="40" :src="'/storage/'+m.imagem"></td> -->
                </tr>
            </tbody>
        </table>
        <!-- {{ dados }} -->
    </div>
</template>

<script>
    export default {
        props: ['dados', 'titulos']
    }
</script>
