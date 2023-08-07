<template>
    <div>
        <table class="table table-hover">
            <thead>
                <tr >
                    <th scope="col" v-for="t, key in titulos" :key="key">{{ t.titulo }}</th>
                    <th v-if="visualizar || atualizar || remover"></th>                  
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
                    <td v-if="visualizar || atualizar || remover">
                        <button v-if="visualizar" class="btn btn-outline-primary  btn-sm">Visualizar</button>
                        <button v-if="atualizar" class="btn btn-outline-primary ms-2 btn-sm">Atualizar</button>
                        <button v-if="remover" class="btn btn-outline-danger ms-2 btn-sm">Remover</button>
                    </td>
                </tr>
                
            </tbody>
        </table>
        <!-- {{ dados }} -->
    </div>
</template>

<script>
    export default {
        props: ['dados', 'titulos', 'atualizar', 'visualizar', 'remover'],
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
