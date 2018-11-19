import React from 'react'
import {PropTypes as T} from 'prop-types'

import {trans} from '#/main/app/intl/translation'

import {Workspace as WorkspaceType} from '#/main/core/workspace/prop-types'
import {WorkspaceCard} from '#/main/core/workspace/data/components/workspace-card'
import {EmptyPlaceholder} from '#/main/core/layout/components/placeholder'

const WorkspaceDisplay = (props) => props.data ?
  <WorkspaceCard
    data={props.data}
    size="sm"
    orientation="col"
  /> :
  <EmptyPlaceholder
    size="lg"
    icon="fa fa-books"
    title={trans('no_workspace')}
  />

WorkspaceDisplay.propTypes = {
  data: T.shape(WorkspaceType.propTypes)
}

export {
  WorkspaceDisplay
}