import axios from 'axios'
import React, { useEffect, useState } from 'react'
import { render } from 'react-dom'

const getArticle = async (articleSlug, loader, successCallback) => {
    try {
        const response = await axios.get('http://localhost:8082/api/articles/' + articleSlug)

        loader()
        successCallback(response.data)
    } catch (e) {
        loader()
    }
}

const Loader = () => {
    return (
        <div className="spinner-border" role="status">
            <span className="visually-hidden">
                Chargement
            </span>
        </div>
    )
}

const ShowScreen = () => {
    const [loader, setLoader] = useState(false)
    const [article, setArticle] = useState([])

    useEffect(() => {
        const articleSlug = document.getElementById('content').getAttribute('articleSlug')

        setLoader(true)

        getArticle(
            articleSlug,
            () => setLoader(false),
            (data) => setArticle(data)
        )
    }, [])

    return (
        <>
            {
                true === loader && <Loader />
            }
            {
                null !== article &&
                <>
                    <dl>
                        <dt>
                            Titre :
                        </dt>
                        <dd>
                            {article.title}
                        </dd>
                        {
                            null !== article.leading &&
                            <>
                                <dt>
                                    Accroche :
                                </dt>
                                <dd>
                                    {article.leading}
                                </dd>
                            </>
                        }
                        {
                            null !== article.body &&
                            <>
                                <dt>
                                    Article :
                                </dt>
                                <dd>
                                    {article.body}
                                </dd>
                            </>
                        }
                        <dt>
                            Auteur :
                        </dt>
                        <dd>
                            {article.createdBy}
                        </dd>
                    </dl>
                </>
            }
        </>
    )
}

render(<ShowScreen />, document.getElementById('content'))
