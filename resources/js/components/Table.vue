<template>
    <div>
        <table class="table table-hover">
            <thead>
                <tr >
                    <th scope="col" v-for="t, key in titulos" :key="key">{{ t.titulo }}</th>                  
                </tr>
            </thead>
            <tbody>
                <tr v-for="obj, chave in dadosFiltrados" :key="chave">
                    <td v-for="valor, chaveValor in obj" :key="chaveValor">
                        <span v-if="titulos[chaveValor].tipo == 'text'">{{ valor }}</span>
                        <span v-if="titulos[chaveValor].tipo == 'data'">{{ '...'+valor }}</span>
                        <span v-if="titulos[chaveValor].tipo == 'imagem'">
                            <img :src="'/storage/' +valor" width="40">
                        </span>
                       
                    </td>
                </tr>
                
            </tbody>
        </table>
        <!-- {{ dados }} -->
    </div>
</template>

<script>
    export default {
        props: ['dados', 'titulos'],
        computed: {
            dadosFiltrados(){
                
                console.log(this.dados)
                let campos = Object.keys(this.titulos)
                let dadosFiltrados = []
                this.dados.map((item, chave) => {
                    console.log(chave, item)

                    let itemFiltrado = {}
                    campos.forEach(campo => {
                        itemFiltrado[campo] = item[campo]
                        
                    })
                    dadosFiltrados.push(itemFiltrado)
                })
                console.log(dadosFiltrados)
                return  (dadosFiltrados)
            }
        }
    }
</script>
