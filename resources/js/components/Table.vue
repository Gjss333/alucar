<template>
    <div>
        <table class="table table-hover">
            <thead>
                <tr >
                    <th scope="col" v-for="t, key in titulos" :key="key">{{ t.titulo }}</th>
                    <th v-if="visualizar.visivel || atualizar.visivel || remover.visivel"></th>                  
                </tr>
            </thead>
            <tbody>
                <tr v-for="obj, chave in dadosFiltrados" :key="chave">
                    <td v-for="valor, chaveValor in obj" :key="chaveValor">
                        <span v-if="titulos[chaveValor].tipo == 'text'">{{ valor }}</span>
                        <span v-if="titulos[chaveValor].tipo == 'data'">
                            {{ formataDataTempo(valor) }}
                        </span>
                        <span v-if="titulos[chaveValor].tipo == 'imagem'">
                            <img :src="'/storage/' +valor" width="40">
                        </span>
                    </td>
                    <td v-if="visualizar.visivel || atualizar.visivel || remover.visivel">
                        <button v-if="visualizar.visivel" class="btn btn-outline-primary btn-sm" :data-bs-toggle="visualizar.dataBsToggle"  :data-bs-target="visualizar.dataBsTarget" @click="setStore(obj)">Visualizar</button>
                        <button v-if="atualizar.visivel" class="btn btn-outline-primary ms-2 btn-sm" :data-bs-toggle="atualizar.dataBsToggle"  :data-bs-target="atualizar.dataBsTarget" @click="setStore(obj)">Atualizar</button>
                        <button v-if="remover.visivel" class="btn btn-outline-danger ms-2 btn-sm" :data-bs-toggle="remover.dataBsToggle"  :data-bs-target="remover.dataBsTarget" @click="setStore(obj)">Remover</button>
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
        methods: {
            setStore(obj){
                this.$store.state.transacao.status = ''
                this.$store.state.transacao.mensagem = ''
                this.$store.state.transacao.dados = ''
                this.$store.state.item = obj
                
            },
            formataDataTempo(data){

                if(!data) return ''
                data = data.split('T')

                let dataFormatada = data[0]
                dataFormatada = dataFormatada.split('-')
                dataFormatada = dataFormatada[2] + '/' + dataFormatada[1] + '/' + dataFormatada[0]

                let horaFormatada = data[1]  
                horaFormatada = horaFormatada.split('.')
                horaFormatada = horaFormatada[0]   
                let dataFinal = dataFormatada+' '+horaFormatada
                
                return dataFinal

            }
        },
        computed: {
            dadosFiltrados(){
                
                let campos = Object.keys(this.titulos)
                let dadosFiltrados = []
                this.dados.map((item, chave) => {

                    let itemFiltrado = {}
                    campos.forEach(campo => {
                        itemFiltrado[campo] = item[campo]
                        
                    })
                    dadosFiltrados.push(itemFiltrado)
                })
                return  (dadosFiltrados)
            }
        }
    }
</script>
