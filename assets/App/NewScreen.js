import axios from 'axios'
import React from 'react'
import { render } from 'react-dom'

const newArticle = async (data, loader, successCallback, errorCallback) => {
    try {
        const response = await axios.post(
            'http://localhost:8082/api/articles',
            data,
            {
                headers: {
                    'accept': 'application/ld+json',
                    'Content-Type': 'application/ld+json',
                    'Accept-Language': 'fr'
                }
            }
        )

//        loader()
        successCallback()
    } catch (e) {
        errorCallback(e.response)

  //      loader()
    }
}

const Success = () => {
    return (
        <div class="alert alert-success">
            L'article a été créé avec succès.
        </div>
    )
}

const Submit = () => {
    return (
        <button
            type="submit"
            className="btn btn-success"
            onClick={(e) => {
                e.preventDefault()

                document.querySelectorAll('[data-error]').forEach(element => {
                    element.remove()
                })

                const title = document.querySelector('#article_title').value
                const leading = document.querySelector('#article_leading').value
                const body = document.querySelector('#article_body').value
                const createdBy = document.querySelector('#article_createdBy').value

                newArticle(
                    {
                        title,
                        leading,
                        body,
                        createdBy
                    },
                    () => console.log('loader à faire'),
                    () => render(<Success />, document.getElementById('content')),
                    (response) => {
                        if (
                            422 === response.status
                                &&
                            undefined !== response.data.violations
                        ) {
                            response.data.violations.forEach(violation => {
                                const container = document.getElementById('article_' + violation.propertyPath)

                                container.insertAdjacentHTML(
                                    'afterend',
                                    '<div data-error>' + violation.message + '</div>'
                                )
                            })
                        }
                    },
                )
            }}
        >
            Valider
        </button>
    )
}

render(<Submit />, document.getElementById('form-submit'))
